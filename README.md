# nethserver-flashstart

**WARNING:** When this package is installed, nethserver-mail-filter will not work.

## Usage from commandl ine

After registering at http://flashstart.nethesis.it,
configure Flashstart and enable access to Unbound:

```
config setprop flashstart status enabled
config setprop unbound access $(config getprop flashstart Roles)
config setprop flashstart Password <pass>
config setprop flashstart Username <user>

signal-event nethserver-flashstart-save
```

## Testing

Execute a DNS query using unbound:

```
dig -t A +noall +answer <domain> @localhost -p $(config getprop unbound UDPPort)
```

If ``<domain>`` is blocked, the server will respond ``188.94.192.215`` or ``45.76.84.187``.
