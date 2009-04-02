<?php
/**
 * PHP Reader Library
 *
 * Copyright (c) 2008-2009 The PHP Reader Project Workgroup. All rights
 * reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *  - Neither the name of the project workgroup nor the names of its
 *    contributors may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    php-reader
 * @subpackage ASF
 * @copyright  Copyright (c) 2008-2009 The PHP Reader Project Workgroup
 * @license    http://code.google.com/p/php-reader/wiki/License New BSD License
 * @version    $Id$
 */

/**#@+ @ignore */
require_once("ASF/Object.php");
/**#@-*/

/**
 * The <i>Error Correction Object</i> defines the error correction method. This
 * enables different error correction schemes to be used during content
 * creation. The <i>Error Correction Object</i> contains provisions for opaque
 * information needed by the error correction engine for recovery. For example,
 * if the error correction scheme were a simple N+1 parity scheme, then the
 * value of N would have to be available in this object.
 * 
 * Note that this does not refer to the same thing as the <i>Error Correction
 * Type</i> field in the <i>{@link ASF_Object_StreamProperties Stream Properties
 * Object}</i>.
 *
 * @package    php-reader
 * @subpackage ASF
 * @author     Sven Vollbehr <svollbehr@gmail.com>
 * @copyright  Copyright (c) 2008-2009 The PHP Reader Project Workgroup
 * @license    http://code.google.com/p/php-reader/wiki/License New BSD License
 * @version    $Rev$
 */
final class ASF_Object_ErrorCorrection extends ASF_Object
{
  /** @var string */
  private $_type;
  
  /** @var string */
  private $_data;
  
  /**
   * Constructs the class with given parameters and reads object related data
   * from the ASF file.
   *
   * @param Reader $reader  The reader object.
   * @param Array  $options The options array.
   */
  public function __construct($reader = null, &$options = array())
  {
    parent::__construct($reader, $options);
    
    if ($reader === null)
      return;
    
    $this->_type = $this->_reader->readGUID();
    $dataLength = $this->_reader->readUInt32LE();
    $this->_data = $this->_reader->read($dataLength);
  }
  
  /**
   * Returns the type of error correction.
   *
   * @return string
   */
  public function getType() { return $this->_type; }
  
  /**
   * Sets the type of error correction.
   * 
   * @param string $type The type of error correction.
   */
  public function setType($type) { $this->_type = $type; }
  
  /**
   * Returns the data specific to the error correction scheme. The structure for
   * the <i>Error Correction Data</i> field is determined by the value stored in
   * the <i>Error Correction Type</i> field.
   *
   * @return Array
   */
  public function getData() { return $this->_data; }
  
  /**
   * Sets the data specific to the error correction scheme. The structure for
   * the <i>Error Correction Data</i> field is determined by the value stored in
   * the <i>Error Correction Type</i> field.
   * 
   * @param Array $data The error correction specific data.
   */
  public function setData($data) { $this->_data = $data; }
  
  /**
   * Returns the whether the object is required to be present, or whether
   * minimum cardinality is 1.
   * 
   * @return boolean
   */
  public function isMandatory() { return false; }
  
  /**
   * Returns whether multiple instances of this object can be present, or
   * whether maximum cardinality is greater than 1.
   * 
   * @return boolean
   */
  public function isMultiple() { return false; }
  
  /**
   * Returns the object data with headers.
   *
   * @return string
   */
  public function __toString()
  {
    $data =
      Transform::toGUID($this->_type) .
      Transform::toUInt32LE(strlen($this->_data)) . $this->_data;
    $this->setSize(24 /* for header */ + strlen($data));
    return
      Transform::toGUID($this->getIdentifier()) .
      Transform::toInt64LE($this->getSize()) . $data;
  }
}
