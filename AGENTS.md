# Project instructions

## Sensitive configuration: mandatory

- **Never read `config.yaml` under any circumstances.** It contains sensitive authentication data.
- This includes direct reads, repository searches, diffs, scripts, test helpers, and running programs that load it. Do not copy, modify, stage, print, or upload it.
- Use `config.yaml.sample` for redacted configuration context and `testdata/parser/` for test fixtures.
- Exclude the sensitive filename explicitly from broad searches, including ignored files:
  `rg -n '<pattern>' --glob '!config.yaml' --glob '!**/config.yaml'`.
- Do not run the application with its default configuration, even with `--dry-run`: it defaults to reading `config.yaml`. Do not execute the benchmark data generator; it also loads that filename and writes NetBox objects.
- Do not execute the sample configuration against its endpoints. Redacted credentials do not make the configured infrastructure a test environment.
- Keep credentials out of logs, fixtures, documentation, and commits. Preserve the user's existing untracked `config.yaml.sample`.

## Purpose and repository map

Netbox-SSOT is a Go command that synchronizes external infrastructure inventory into NetBox. Module:
`github.com/bl4ko/netbox-ssot`. It runs once per invocation and can be scheduled as a Kubernetes CronJob.

| Location | Responsibility |
| --- | --- |
| `cmd/netbox-ssot/main.go` | CLI flags, initialization, concurrent source execution, cleanup and exit status |
| `internal/parser/` | YAML structs, defaults, custom unmarshalling, validation |
| `internal/source/source.go` | Source factory and per-source name/type tags |
| `internal/source/common/` | Source interface, shared configuration, relationship/filter helpers |
| `internal/source/<provider>/` | Provider connection, data collection, conversion and synchronization |
| `internal/netbox/objects/` | NetBox models, relationships, statuses and object interfaces |
| `internal/netbox/mapper/` | Reflection-based mapping of model types to API paths |
| `internal/netbox/service/` | HTTP client, generic CRUD, pagination and dry-run behavior |
| `internal/netbox/inventory/` | Shared indexed inventory, add/update operations, predefined items and orphan tracking |
| `internal/utils/` | JSON diffs/marshalling, HTTP/TLS, networking and naming helpers |
| `internal/constants/` | Source types, API paths, content types, names, limits and colors |
| `internal/logger/` | Logging with source context |
| `testdata/` | Parser and certificate fixtures |
| `.github/workflows/`, `Dockerfile`, `k8s/` | CI, image packaging and deployment |

The factory currently supports oVirt, VMware, DNAC, Proxmox, Palo Alto, FortiGate, FMC, IOS-XE, F5,
Hetzner Cloud and OpenStack. Existing `CLAUDE.md` and parts of `README.md` lag the implementation;
verify behavior against code rather than copying their versions, source lists or configuration claims.

## Execution and behavior to preserve

1. Parse configuration and initialize source-aware logging.
2. Construct the shared inventory, check NetBox version, and initialize objects and indexes in order.
3. Create source tags through the factory. Each source implements `Init() error` and
   `Sync(*inventory.NetboxInventory) error`; initialization collects provider data and sync converts it.
4. Run source initialization/sync in goroutines, sharing the inventory.
5. After waiting for sources, attempt orphan cleanup only when all sources report success. A failed
   source skips cleanup and makes the command exit with failure.

- Inventory initialization order establishes relationships; do not reorder it casually.
- Use inventory `Add*` methods for source changes. They handle locks, lookup keys, differences, API
  writes, indexes and orphan bookkeeping. Preserve every affected primary and secondary index.
- Inventory has per-object-type mutexes. Shared maps and source completion/error state need proper
  synchronization; do not assume the existing orchestration is race-free.
- Preserve source tags and `source`/`source_id` custom fields. Source priority and ARP precedence are
  implemented in `internal/utils/diff_map.go`; lower configured priority indexes win conflicts.
- Orphan tracking is based on the SSOT management tag. `removeOrphans: true` selects hard deletion;
  otherwise cleanup marks orphans and uses `removeOrphansAfterDays` for retention. Inspect
  `delete_items.go` and `orphan_manager.go` before changing cleanup or ownership behavior.
- `--dry-run` suppresses NetBox writes in the service layer and uses synthetic IDs for creates.
  Reads and provider authentication still happen. Preserve write suppression when changing CRUD.
- The current version check accepts NetBox major version 4 with minor version >= 2.

## Configuration and provider fixes

- Top-level YAML sections are `logger`, `netbox`, and **`source`** (singular). The Go field is `Sources`.
- `internal/parser/parser.go` is authoritative for YAML keys, defaults and validation. Parser changes
  should update focused tests/fixtures and relevant public documentation.
- Relationship rules use `regex = object name` strings. Reuse common matching and subnet/interface
  filtering helpers instead of adding inconsistent provider-specific interpretations.
- Provider files generally split into `<provider>.go`, `<provider>_init.go`, and `<provider>_sync.go`.
- For new providers, implement the common interface, register factory/constants/parser validation,
  and use inventory methods with appropriate tags and ownership fields.
- For new NetBox object types, check models/interfaces, API constants and mapper, initialization,
  indexes/getters, add/update methods, and orphan handling together.
- Proxmox, Hetzner Cloud and OpenStack are represented in the sample. Sample options may not all
  be honored by each provider; trace a setting into its consumer before changing or documenting it.

## Development and validation

- `go.mod` currently declares Go `1.25.11`; CI uses the Go `1.25` series. Docker pins its own newer
  builder. Read these files when toolchain compatibility matters.
- Format changed Go files with `gofmt`. Follow `.golangci.yml` (v2 configuration); CI currently pins
  golangci-lint `v2.12.2`. Match surrounding error handling and source-aware context propagation.
- Keep fixes focused. Add regression coverage for behavior changes, preferably table-driven tests
  and existing fixtures or local HTTP test servers. Tests must not depend on live infrastructure.
- Start with affected packages, for example:
  `go test ./internal/parser ./internal/source/openstack ./internal/netbox/inventory`.
- For changes spanning shared state or providers, run `go test -race ./...` when the environment allows.
  CI runs `go test -race -coverprofile=coverage.txt -covermode=atomic ./...` and
  `golangci-lint run --timeout=5m`.
- A compile-only check is `go build -o /tmp/netbox-ssot-check ./cmd/netbox-ssot`; do not execute the binary
  as part of routine verification. Building does not require loading runtime configuration.
- Inspect unfamiliar test entrypoints before running them to ensure they cannot load the prohibited
  file. Report unavailable tooling or failed checks accurately.
- `make build_and_push` publishes a container image. Use it only when publishing is explicitly requested.
- Report what changed, the checks actually performed, and any remaining limitations. Do not fix
  unrelated issues discovered during investigation without a task that calls for them.
