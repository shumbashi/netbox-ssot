<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS;

class FirewallConstants
{
    const FIREWALL = 'firewall';

    const DIRECTION = 'direction';

    const DIRECTION_INBOUND  = 'inbound';
    const DIRECTION_OUTBOUND = 'outbound';

    const DIRECTION_INGRESS = 'ingress';
    const DIRECTION_EGRESS  = 'egress';

    const DIRECTIONS = [self::DIRECTION_INGRESS, self::DIRECTION_EGRESS];

    const RULE_TCP    = 'Tcp';
    const RULE_UPD    = 'Udp';
    const RULE_ICMP   = 'Icmp';
    const RULE_CUSTOM = 'custom';

    const RULES = [self::RULE_TCP, self::RULE_UPD, self::RULE_ICMP, self::RULE_CUSTOM];

    const IPV4 = 'IPv4';
    const IPV6 = 'IPv6';

    const PROTOCOL          = 'protocol';
    const SECURITY_GROUP_ID = 'security_group_id';
    const REMOTE_IP_PREFIX  = 'remote_ip_prefix';

    const ETHER_TYPE  = 'ethertype';
    const ETHER_TYPES = [self::IPV4, self::IPV6];

    const OPEN_PORT_PORT       = 'port';
    const OPEN_PORT_PORT_RANGE = 'portRange';
    const OPEN_PORT_ALL_PORTS  = 'allPorts';

    const OPEN_PORT_VARIABLE = [self::OPEN_PORT_PORT, self::OPEN_PORT_PORT_RANGE, self::OPEN_PORT_ALL_PORTS];

    const CUSTOM_PORT = 'customPort';
}