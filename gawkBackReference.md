##Using gawk instead of awk to reference captured groups

```bash
gawk 'match(str, /pattern/, var) { print var[1]; }'
```

###example
I used this to find the list of hosts to which a server was initiating thousands of connections, and 
interrupting legitimate inbound ssh traffic.
```bash
cat /var/log/audit/audit.log | \
  gawk 'match($0,/addr=([[:digit:]]+\.[[:digit:]]+\.[[:digit:]]+\.[[:digit:]]+)/,m) {print m[1];}' \
  | sort | uniq | xargs -L1 host | awk '{print $5}'
```
