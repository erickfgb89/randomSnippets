import curses
import socket
import threading
import json
import gnupg
import os
import pyperclip
from getpass import getpass
from datetime import datetime
import re

DEFAULT_HOST = '127.0.0.1'
PORT = 12345
gpg = gnupg.GPG()
KEY_DIR = "keys"
USERNAME = input("Enter your username: ")
MY_KEY_ID = input("Enter your GPG key ID (e.g., email or key ID): ")
PASSPHRASE = getpass("Enter your GPG key passphrase (hidden): ")

# Ensure keys directory exists
if not os.path.exists(KEY_DIR):
    os.makedirs(KEY_DIR)

def sanitize_filename(s):
    """Sanitize key ID for use as a filename (e.g., remove invalid chars)."""
    return re.sub(r'[^\w.-]', '_', s)

def import_keys():
    imported = 0
    for key_file in os.listdir(KEY_DIR):
        if key_file.endswith(".pub"):
            try:
                with open(os.path.join(KEY_DIR, key_file), 'r') as f:
                    result = gpg.import_keys(f.read())
                    imported += result.count
                    if result.count == 0:
                        print(f"Failed to import key from {key_file}: {result.stderr}")
            except Exception as e:
                print(f"Error reading {key_file}: {str(e)}")
    return imported

def encrypt_message(message, key_id):
    key_file = os.path.join(KEY_DIR, f"{sanitize_filename(key_id)}.pub")
    if not os.path.exists(key_file):
        return None, f"No public key found for {key_id} at {key_file}"
    try:
        with open(key_file, 'r') as f:
            result = gpg.import_keys(f.read())
            if result.count == 0:
                return None, f"Failed to import key for {key_id}: {result.stderr}"
    except Exception as e:
        return None, f"Error reading key file for {key_id}: {str(e)}"
    encrypted = gpg.encrypt(message, recipients=[key_id], always_trust=True)
    if not encrypted.ok:
        return None, f"Encryption failed: {encrypted.stderr}"
    return str(encrypted), None

def decrypt_message(message, username):
    if username == "Server":
        return message  # Server messages are plaintext
    decrypted = gpg.decrypt(message, passphrase=PASSPHRASE)
    if not decrypted.ok:
        return f"[Decryption failed: {decrypted.stderr}]"
    return str(decrypted)

def draw_window(stdscr, user_win, chat_win, input_win, users, messages, input_text):
    stdscr.clear()
    user_win.clear()
    chat_win.clear()
    input_win.clear()

    # Draw user list
    user_win.box()
    user_win.addstr(0, 2, "Users")
    for i, user in enumerate(users, 1):
        user_win.addstr(i, 2, user[:18])

    # Draw chat window
    chat_win.box()
    chat_win.addstr(0, 2, "Chat")
    for i, msg in enumerate(messages[-15:], 1):  # Show last 15 messages
        text = f"{msg['username']}: {msg['text']}"[:78]
        chat_win.addstr(i, 2, text)

    # Draw input window
    input_win.box()
    input_win.addstr(0, 2, "Input")
    input_win.addstr(1, 2, input_text[:78])

    stdscr.refresh()
    user_win.refresh()
    chat_win.refresh()
    input_win.refresh()

def main(stdscr):
    curses.curs_set(0)
    stdscr.timeout(100)  # Non-blocking input
    height, width = stdscr.getmaxyx()

    # Create windows
    user_win = curses.newwin(height-3, 20, 0, 0)
    chat_win = curses.newwin(height-3, width-20, 0, 20)
    input_win = curses.newwin(3, width, height-3, 0)

    # Initialize state
    users = []
    messages = []
    input_text = ""
    client_socket = None
    receive_thread = None
    connected = False

    # Validate key ID
    key_list = gpg.list_keys(secret=True)
    key_ids = [key['keyid'] for key in key_list] + [uid for key in key_list for uid in key['uids']]
    if MY_KEY_ID not in key_ids:
        messages.append({"username": "System", "text": f"Warning: GPG key ID {MY_KEY_ID} not found in keyring"})

    # Import public keys
    imported_count = import_keys()
    messages.append({"username": "System", "text": f"Imported {imported_count} public keys"})

    # Check for user's public key
    key_file = os.path.join(KEY_DIR, f"{sanitize_filename(MY_KEY_ID)}.pub")
    if not os.path.exists(key_file):
        messages.append({"username": "System", "text": f"Warning: Public key file {key_file} not found"})

    def start_receive_thread(sock):
        def receive_messages():
            while True:
                try:
                    data = sock.recv(1024).decode('utf-8')
                    if not data:
                        break
                    data = json.loads(data)
                    if data["type"] == "user_list":
                        users[:] = data["users"]
                    elif data["type"] == "message":
                        decrypted_text = decrypt_message(data["text"], data["username"])
                        messages.append({"username": data["username"], "text": decrypted_text})
                    elif data["type"] == "error":
                        messages.append({"username": "System", "text": data["message"]})
                except:
                    messages.append({"username": "System", "text": "Disconnected from server"})
                    break
        thread = threading.Thread(target=receive_messages, daemon=True)
        thread.start()
        return thread

    def connect_to_server(ip):
        nonlocal client_socket, receive_thread, connected
        # Close existing connection if any
        if client_socket:
            client_socket.close()
        if receive_thread:
            receive_thread = None  # Thread will terminate when socket closes
        # Create new socket
        client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        try:
            client_socket.connect((ip, PORT))
            client_socket.send(json.dumps({"username": USERNAME}).encode('utf-8'))
            connected = True
            receive_thread = start_receive_thread(client_socket)
            messages.append({"username": "System", "text": f"Connected to {ip}:{PORT}"})
        except Exception as e:
            client_socket.close()
            client_socket = None
            connected = False
            messages.append({"username": "System", "text": f"Connection failed: {str(e)}"})

    # Initial connection attempt
    connect_to_server(DEFAULT_HOST)

    while True:
        try:
            draw_window(stdscr, user_win, chat_win, input_win, users, messages, input_text)
            char = stdscr.getch()
            if char == -1:  # No input
                continue
            if char == 10:  # Enter key
                if input_text.strip():
                    command = input_text.strip().lower()
                    if command in ["/quit", "/exit"]:
                        messages.append({"username": "System", "text": "Exiting chat..."})
                        draw_window(stdscr, user_win, chat_win, input_win, users, messages, input_text)
                        break
                    elif command.startswith("/connect "):
                        ip = command[9:].strip()
                        if ip:
                            connect_to_server(ip)
                        else:
                            messages.append({"username": "System", "text": "Usage: /connect <IP>"})
                        input_text = ""
                    elif connected:
                        encrypted_msg, error = encrypt_message(input_text.strip(), MY_KEY_ID)
                        if encrypted_msg:
                            client_socket.send(json.dumps({"text": encrypted_msg}).encode('utf-8'))
                        else:
                            messages.append({"username": "System", "text": f"Cannot send message: {error}"})
                        input_text = ""
                    else:
                        messages.append({"username": "System", "text": "Not connected to a server"})
                        input_text = ""
                else:
                    input_text = ""
            elif char == 27:  # Escape key
                messages.append({"username": "System", "text": "Exiting chat via Escape..."})
                draw_window(stdscr, user_win, chat_win, input_win, users, messages, input_text)
                break
            elif char == 22:  # Ctrl+V for paste
                input_text += pyperclip.paste()[:78]
            elif char == 127 or char == 8:  # Backspace
                input_text = input_text[:-1]
            elif char == 263: # Delete key (replace with your key code if needed, e.g., 331)
                input_text = input_text[:-1]
            elif 32 <= char <= 126:  # Printable characters
                input_text += chr(char)
        except Exception as e:
            messages.append({"username": "System", "text": f"Error: {str(e)}"})
            break

    # Clean up
    if client_socket:
        client_socket.close()

if __name__ == "__main__":
    curses.wrapper(main)
