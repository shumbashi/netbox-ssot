package hetznercloud

import (
	"context"
	"io"
	"net/http"
	"strings"
	"testing"

	"github.com/hetznercloud/hcloud-go/v2/hcloud"
)

type hetznerTestTransport func(*http.Request) (*http.Response, error)

func (transport hetznerTestTransport) RoundTrip(req *http.Request) (*http.Response, error) {
	return transport(req)
}

func TestInitDataWithoutDatacenters(t *testing.T) {
	responses := map[string]string{
		"/v1/locations": `{"locations":[{"id":1,"name":"fsn1","city":"Falkenstein"}]}`,
		"/v1/servers": `{"servers":[{"id":42,"name":"test-server",
			"location":{"id":1,"name":"fsn1","city":"Falkenstein"}}]}`,
		"/v1/networks":     `{"networks":[{"id":2,"name":"private","ip_range":"10.0.0.0/8"}]}`,
		"/v1/floating_ips": `{"floating_ips":[{"id":3,"ip":"192.0.2.1","type":"ipv4"}]}`,
		"/v1/primary_ips": `{"primary_ips":[{"id":4,"ip":"192.0.2.2","type":"ipv4",
			"location":{"id":1,"name":"fsn1","city":"Falkenstein"}}]}`,
	}
	visited := make(map[string]bool)
	client := hcloud.NewClient(hcloud.WithHTTPClient(&http.Client{
		Transport: hetznerTestTransport(func(req *http.Request) (*http.Response, error) {
			visited[req.URL.Path] = true
			body, ok := responses[req.URL.Path]
			status := http.StatusOK
			if !ok {
				t.Errorf("unexpected endpoint: %s", req.URL.Path)
				status = http.StatusGone
				body = `{"error":{"code":"deprecated_api_endpoint","message":"API functionality was removed"}}`
			}
			return &http.Response{
				StatusCode: status,
				Header:     http.Header{"Content-Type": []string{"application/json"}},
				Body:       io.NopCloser(strings.NewReader(body)),
				Request:    req,
			}, nil
		}),
	}))

	hcs := newTestSource()
	if err := hcs.initData(context.Background(), client); err != nil {
		t.Fatalf("initialization with current API responses failed: %v", err)
	}
	for path := range responses {
		if !visited[path] {
			t.Errorf("endpoint was not fetched: %s", path)
		}
	}
	if len(hcs.Locations) != 1 || len(hcs.Servers) != 1 || len(hcs.Networks) != 1 ||
		len(hcs.FloatingIPs) != 1 || len(hcs.PrimaryIPs) != 1 {
		t.Fatal("expected all supported resources to be initialized")
	}
	if hcs.Servers[0].Location == nil || hcs.Servers[0].Location.City != hcs.Locations[0].City {
		t.Error("server location should resolve to its site city without a datacenter attribute")
	}
}

func TestInitDataPropagatesAPIFailure(t *testing.T) {
	client := hcloud.NewClient(hcloud.WithHTTPClient(&http.Client{
		Transport: hetznerTestTransport(func(req *http.Request) (*http.Response, error) {
			return &http.Response{
				StatusCode: http.StatusUnauthorized,
				Header:     http.Header{"Content-Type": []string{"application/json"}},
				Body: io.NopCloser(strings.NewReader(
					`{"error":{"code":"unauthorized","message":"invalid token"}}`,
				)),
				Request: req,
			}, nil
		}),
	}))
	hcs := newTestSource()
	err := hcs.initData(context.Background(), client)
	if err == nil || !strings.Contains(err.Error(), "fetching locations") ||
		!strings.Contains(err.Error(), "unauthorized") {
		t.Fatalf("expected initialization to fail on authentication error, got %v", err)
	}
}
