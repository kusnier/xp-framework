<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'unittest.TestCase',
    'rdbms.DriverManager'
  );

  /**
   * Base class for Rdbms tests
   *
   */
  abstract class RdbmsIntegrationTest extends TestCase {

    /**
     * Retrieve dsn
     *
     * @return  string
     */
    abstract public function _dsn();
  
    /**
     * Sets up test case
     *
     */
    public function setUp() {
      // TODO: Fill code that gets executed before every test method
      //       or remove this method
    }
    
    /**
     * (Insert method's description here)
     *
     * @param   
     * @return  
     */
    public function tearDown() {
      // TODO: Fill code that gets executed after every test method
      //       or remove this method
    }
    
    /**
     * (Insert method's description here)
     *
     * @param   
     * @return  
     */
    protected function db() {
      with ($db= DriverManager::getConnection($this->_dsn())); {
        $db->connect();
      }
      
      return $db;
    }    
    
    /**
     * (Insert method's description here)
     *
     * @param   
     * @return  
     */
    #[@test]
    public function connect() {
      $this->db()->close();
    }
    
    /**
     * Test
     *
     */
    #[@test]
    public function simpleStaticQuery() {
      $this->assertEquals(
        array(array('foo' => 1)), 
        $this->db()->select('1 as foo')
      );
    }
    
    /**
     * Test
     *
     */
    #[@test]
    public function simpleQuery() {
      $q= $this->db()->query('select 1 as foo');
      $this->assertSubclass($q, 'rdbms.ResultSet');
      $this->assertEquals(1, $q->next('foo'));
    }
    
    /**
     * (Insert method's description here)
     *
     * @param   
     * @return  
     */
    protected function createTable() {
      // Try to remove, if already exist...
      try {
        $this->db()->query('drop table unittest');
      } catch (SQLStatementFailedException $ignored) {}

      $this->db()->query('create table unittest (pk int, username varchar(30))');
    }
    
    /**
     * Test
     *
     */
    #[@test]
    public function insertIntoTable() {
      $this->createTable();
      $q= $this->db()->query('insert into unittest values (1, "kiesel")');
      $this->assertEquals(TRUE, $q);
      
      $q= $this->db()->insert('into unittest values (2, "xp")');
      $this->assertEquals(1, $q);
    }
    
  }
?>