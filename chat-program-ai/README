import socket
import threading
import json

HOST = '0.0.0.0'  # Listen on all interfaces
PORT = 12345
clients = {}  # {client_socket: username}
usernames = set()

def broadcast(message, sender_socket=None):
    for client in clients:
        if client != sender_socket:
            try:
                client.send(json.dumps(message).encode('utf-8'))
            except:
                client.close()
                del clients[client]
                usernames.remove(clients[client])
                broadcast({"type": "user_list", "users": list(usernames)})

def handle_client(client_socket):
    try:
        # Receive username
        data = json.loads(client_socket.recv(1024).decode('utf-8'))
        username = data["username"]
        if username in usernames:
            client_socket.send(json.dumps({"type": "error", "message": "Username taken"}).encode('utf-8'))
            client_socket.close()
            return
        clients[client_socket] = username
        usernames.add(username)
        broadcast({"type": "user_list", "users": list(usernames)})
        broadcast({"type": "message", "username": "Server", "text": f"{username} joined the chat"})

        while True:
            message = client_socket.recv(1024).decode('utf-8')
            if not message:
                break
            data = json.loads(message)
            broadcast({"type": "message", "username": username, "text": data["text"]})
    except:
        pass
    finally:
        if client_socket in clients:
            username = clients[client_socket]
            del clients[client_socket]
            usernames.remove(username)
            broadcast({"type": "user_list", "users": list(usernames)})
            broadcast({"type": "message", "username": "Server", "text": f"{username} left the chat"})
        client_socket.close()

def start_server():
    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server.bind((HOST, PORT))
    server.listen()
    print(f"Server running on {HOST}:{PORT}")
    while True:
        client_socket, addr = server.accept()
        threading.Thread(target=handle_client, args=(client_socket,), daemon=True).start()

if __name__ == "__main__":
    start_server()
