<?php

declare(strict_types=1);

auth()->config("session", true);
auth()->config("session.lifetime", $_ENV["SESSION_LIFETIME"]);
