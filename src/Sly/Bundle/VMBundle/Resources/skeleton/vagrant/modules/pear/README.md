# Puppet module: pear

**Written by laupifrpar**

Licence: Apache2

## DESCRIPTION
This module installs pear components

## USAGE

### Installs pear support

    include pear

### Installs pear component using OS package

    pear::module {
        'Crypt-CHAP':
    }

### Installs pear component with pear command

    pear::module {
        "XML_Serializer":
            use_package => "no",
            preferred_state => "beta"
    }

### Configures pear environment

    pear::config {
        "http_proxy":
            value => "$proxy_server"
    }
