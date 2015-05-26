<?php

   namespace Alo\Session;

   use Alo;
   use Alo\Db\MySQL;

   if (!defined('GEN_START')) {
      http_response_code(404);
      die();
   }

   /**
    * The SQL-based session handler. ALO_SESSION_CLEANUP is not used here as
    * cleanup is handled by the MySQL event handler
    *
    * @author  Art <a.molcanovas@gmail.com>
    * @package Session
    */
   class SQLSession extends AbstractSession {

      /**
       * Reference to database instance
       *
       * @var MySQL
       */
      protected $db;

      /**
       * Instantiates the class
       *
       * @author Art <a.molcanovas@gmail.com>
       */
      function __construct() {
         if (!Alo::$db) {
            Alo::$db = new MySQL();
         }

         $this->db = &Alo::$db;
         parent::__construct();
         \Log::debug('Initialised MySQL session');
      }

      /**
       * Fetches session data
       *
       * @author Art <a.molcanovas@gmail.com>
       * @return SQLSession
       */
      protected function fetch() {
         $sql = $this->db->prepQuery('SELECT `data` '
                                     . 'FROM `' . ALO_SESSION_TABLE_NAME . '` '
                                     . 'WHERE `id`=? '
                                     . 'LIMIT 1', [$this->id], [
                                        MySQL::V_CACHE => false
                                     ]);

         if (!empty($sql)) {
            $this->data = json_decode($sql[0]['data'], true);
         }

         \Log::debug('Saved session data');

         return $this;
      }

      /**
       * Terminates the session
       *
       * @author Art <a.molcanovas@gmail.com>
       * @return SQLSession
       */
      function terminate() {
         $this->db->prepQuery('DELETE FROM `' . ALO_SESSION_TABLE_NAME . '` '
                              . 'WHERE `id`=? '
                              . 'LIMIT 1', [$this->id], [MySQL::V_CACHE => false]);

         return parent::terminate();
      }

      /**
       * Saves session data
       *
       * @author Art <a.molcanovas@gmail.com>
       * @return SQLSession
       */
      protected function write() {
         $this->db->prepQuery('REPLACE INTO `'
                              . ALO_SESSION_TABLE_NAME . '`(`id`,`data`,`access`) VALUES('
                              . '?,?,CURRENT_TIMESTAMP)', [
                                 $this->id,
                                 json_encode($this->data)
                              ], [MySQL::V_CACHE => false]);

         \Log::debug('Saved session data');

         return $this;
      }

   }