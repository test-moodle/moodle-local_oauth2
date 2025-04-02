<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * OAuth2 clients table.
 *
 * @package local_oauth2
 * @author Lai Wei <lai.wei@enovation.ie>
 * @author Dorel Manolescu <dorel.manolescu@enovation.ie>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright (C) 2025 Enovation Solutions
 */

namespace local_oauth2\table;

use html_table;
use html_writer;
use moodle_url;

/**
 * OAuth clients table.
 */
class oauth_clients_table extends html_table {
    /**
     * Constructor.
     */
    public function __construct() {
        global $DB;

        parent::__construct();

        $this->class = 'generaltable generalbox';
        $this->head = [
            get_string('oauth_client_id', 'local_oauth2'),
            get_string('oauth_client_secret', 'local_oauth2'),
            get_string('oauth_scope', 'local_oauth2'),
            get_string('actions', 'local_oauth2'),
        ];
        $this->align = ['left', 'left', 'center'];

        $clients = $DB->get_records('local_oauth2_client');
        foreach ($clients as $client) {
            $editurl = new moodle_url('/local/oauth2/manage_oauth_clients.php', ['id' => $client->id, 'action' => 'edit']);
            $deleteurl = new moodle_url('/local/oauth2/manage_oauth_clients.php', ['id' => $client->id, 'action' => 'delete']);
            $actions = html_writer::link($editurl, get_string('edit')) . ' | ' .
                html_writer::link($deleteurl, get_string('delete'));
            $row = [
                $client->client_id,
                $client->client_secret,
                $client->scope,
                $actions,
            ];

            $this->data[] = $row;
        }
    }

    /**
     * Output the table.
     */
    public function out() {
        echo html_writer::table($this);
    }
}
