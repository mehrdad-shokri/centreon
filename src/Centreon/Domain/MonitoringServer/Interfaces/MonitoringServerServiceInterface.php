<?php

/*
 * Copyright 2005 - 2019 Centreon (https://www.centreon.com/)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * For more information : contact@centreon.com
 *
 */
declare(strict_types=1);

namespace Centreon\Domain\MonitoringServer\Interfaces;

use Centreon\Domain\MonitoringServer\MonitoringServer;
use Centreon\Domain\MonitoringServer\MonitoringServerException;
use Centreon\Domain\MonitoringServer\MonitoringServerResource;
use Centreon\Domain\MonitoringServer\MonitoringServerService;

interface MonitoringServerServiceInterface
{

    /**
     * Find pollers.
     *
     * @return MonitoringServer[]
     * @throws MonitoringServerException
     */
    public function findServers(): array;

    /**
     * Find a resource of monitoring servers identified by his name.
     *
     * @param int $monitoringServerId Id of the monitoring server for which we want their resources
     * @param string $resourceName Resource name to find
     * @return MonitoringServerResource|null
     * @throws MonitoringServerException
     */
    public function findResource(int $monitoringServerId, string $resourceName): ?MonitoringServerResource;

    /**
     * Find the local monitoring server.
     *
     * @return MonitoringServer|null
     * @throws MonitoringServerException
     */
    public function findLocalServer(): ?MonitoringServer;
}
