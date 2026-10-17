<?php

use function Kipchak\Core\env;

return [
    'connections' => [
        'default' => [
            'host' => env('SFTP_HOST', 'sftp.example.com'),
            'port' => 22,
            'username' => env('SFTP_USERNAME', 'kipchak'),

            // A password, a private key, or both for servers that require both. private_key is the path to a key
            // file or the key itself, for example from a secret in an environment variable.
            'password' => env('SFTP_PASSWORD', ''),
            'private_key' => env('SFTP_PRIVATE_KEY', ''),
            'private_key_passphrase' => env('SFTP_PRIVATE_KEY_PASSPHRASE', ''),

            // The server's host key fingerprints, from: ssh-keyscan sftp.example.com | ssh-keygen -lf -
            // List every key the server has, so a change of preferred key type does not break the connection.
            // The connection is refused, before any credentials are sent, if the server's key is not listed.
            'host_key_fingerprints' => [
                env('SFTP_HOST_KEY_FINGERPRINT', 'SHA256:replace-with-the-servers-fingerprint'),
            ],
            // Only for local development: accept any host key.
            'verify_host_key' => true,

            // Confines every path to this directory, such as /outbound. Paths that climb out of it ("../") are
            // rejected. Empty: paths are used as given, relative ones from the login directory.
            'root' => env('SFTP_ROOT', ''),

            // Seconds to connect and to wait for each response.
            'timeout' => 10,

            // Write to a temporary file and rename it into place, so that whoever collects files from the directory
            // never sees a partial one. Turn off only for servers that do not allow renaming.
            'atomic_writes' => true,
        ],
    ],
];
