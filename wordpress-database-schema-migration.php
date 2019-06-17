<?php

/**
 * Control and execute database schema migrations
 *
 * @link https://github.com/Jakobud/wordpress-database-schema-migration
 * @since 1.0.0
 * @author Jake Wilson <jake.e.wilson@gmail.com>
 */
class Wordpress_Database_Schema_Migration {

  private $migrations = [];
  private $optionNameSuffix = "-database-schema-migration-offset";

  function __construct($pluginName) {
    $this->optionName = $pluginName . $this->optionNameSuffix;
  }

  /**
   * Add a series of sql commands to the migration stack
   */
  public function add($migration) {
    array_push($this->migrations, $migration);
    return $this;
  }

  /**
   * Execute the migration stack starting with the old offset
   */
  public function execute() {
    // Get old migration offset from Wordpress, defaulting to 0 (zero)
    $migrationOffset = get_option($this->optionName, 0);

    // Loop through migrations starting at the old offset
    for ($c=$migrationOffset; $c < sizeof($this->migrations); $c++) {
      call_user_func($this->migrations[$c]);
    }

    // Save the new migration offset into Wordpress
    update_option($this->optionName, $c);
  }
}
