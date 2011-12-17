<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses('rdbms.tds.AbstractTdsResultSet');

  /**
   * Result set
   *
   * @purpose  Resultset wrapper
   */
  class TdsBufferedResultSet extends AbstractTdsResultSet {
    protected $records= array();

    /**
     * Constructor
     *
     * @param   resource handle
     */
    public function __construct($result, $fields, TimeZone $tz= NULL) {
      parent::__construct($result, $fields, $tz);
      try {
        while (NULL !== ($record= $this->handle->fetch($this->fields))) {
          $this->records[]= $record;
        }
      } catch (ProtocolException $e) {
        throw new SQLException('Failed reading rows', $e);
      }
      reset($this->records);
    }
      
    /**
     * Seek
     *
     * @param   int offset
     * @return  bool success
     * @throws  rdbms.SQLException
     */
    public function seek($offset) { 
      throw new SQLException('Cannot seek to offset '.$offset);
    }
    
    /**
     * Iterator function. Returns a rowset if called without parameter,
     * the fields contents if a field is specified or FALSE to indicate
     * no more rows are available.
     *
     * @param   string field default NULL
     * @return  var
     */
    public function next($field= NULL) {
      if (FALSE === ($record= current($this->records))) return FALSE;
      
      next($this->records);
      return $this->record($record, $field);
    }
    
    /**
     * Close resultset and free result memory
     *
     * @return  bool success
     */
    public function close() { 
      $this->handle= NULL;
      return TRUE;
    }
  }
?>
