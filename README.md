# nethserver-flashstart

This package implements DNS filtering using Flashstart servers.

Every request from local zones to port tcp/udp 53, is redirected to Unbound.

Unbound is configured as follow:

- Forward queries for server machine domain to dnsmasq
- Forward reverse queries for all green interfaces to dnsmasq
- Forward every remaining query to Flashstart DNS (``188.94.192.215`` and ``45.76.84.187``)

Please note that queries from the server itself are never filtered.

*Note:* When this package is installed, DNSBL from nethserver-mail-filter may not correctly work.

### Database

Properties:
- ``Bypass``: comma-separeted list of firewall object (or ip addresses) which are not redirect to Unbound.
  Flashstart DNS can't be bypassed if the client is using Squid to surf the web.
- ``Password``: password for Flashstart service
- ``Roles``: comma-separated list of Roles, default to ``green``. The ``red`` role is not allowed.
- ``Username``: user name for Flashstart service
- ``status``: can be ``enabled`` or ``disabled``. Default to ``disabled``.

Example:
```
flashstart=configuration
    Bypass=
    Password=11223344
    Roles=green
    Username=test@nethesis.it
    status=enabled

```


## Usage from command line

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
