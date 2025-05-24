<?php
$server = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
if (!$server) die("$errstr ($errno)");

echo "WebSocket server started...\n";

$clients = [];

while (true) {
  $changed = $clients;
  $read = array_merge([$server], $changed);
  $write = $except = null;

  if (stream_select($read, $write, $except, null) === false) continue;

  foreach ($read as $socket) {
    if ($socket === $server) {
      $client = stream_socket_accept($server, -1);
      $clients[] = $client;
      echo "New client connected\n";
    } else {
      $data = fread($socket, 1024);
      if (!$data) {
        unset($clients[array_search($socket, $clients)]);
        fclose($socket);
      } else {
        foreach ($clients as $client) {
          if ($client !== $socket) fwrite($client, $data);
        }
      }
    }
  }
}
?>