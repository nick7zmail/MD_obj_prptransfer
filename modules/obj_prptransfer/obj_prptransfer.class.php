<?php
/**
* PropTransfer 
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 09:12:00 [Dec 28, 2017])
*/
//
//
class obj_prptransfer extends module {
/**
* obj_prptransfer
*
* Module class constructor
*
* @access private
*/
function obj_prptransfer() {
  $this->name="obj_prptransfer";
  $this->title="PropTransfer";
  $this->module_category="<#LANG_SECTION_OBJECTS#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {
 if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
  $out['SET_DATASOURCE']=1;
 }
 if ($this->data_source=='obj_prptransfer' || $this->data_source=='') {
  if ($this->view_mode=='' || $this->view_mode=='search_obj_prptransfer') {
   $this->search_obj_prptransfer($out);
  }
  if ($this->view_mode=='edit_obj_prptransfer') {
   $this->edit_obj_prptransfer($out, $this->id);
  }
  if ($this->view_mode=='delete_obj_prptransfer') {
   $this->delete_obj_prptransfer($this->id);
   $this->redirect("?");
  }
 }
}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
/**
* obj_prptransfer search
*
* @access public
*/
 function search_obj_prptransfer(&$out) {
  require(DIR_MODULES.$this->name.'/obj_prptransfer_search.inc.php');
 }
/**
* obj_prptransfer edit/add
*
* @access public
*/
 function edit_obj_prptransfer(&$out, $id) {
  require(DIR_MODULES.$this->name.'/obj_prptransfer_edit.inc.php');
 }
/**
* obj_prptransfer delete record
*
* @access public
*/
 function delete_obj_prptransfer($id) {
  $rec=SQLSelectOne("SELECT * FROM obj_prptransfer WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM obj_prptransfer WHERE ID='".$rec['ID']."'");
 }
 function propertySetHandle($object, $property, $value) {
   $table='obj_prptransfer';
   $properties=SQLSelect("SELECT * FROM $table WHERE LINKED_OBJECT LIKE '".DBSafe($object)."' AND LINKED_PROPERTY LIKE '".DBSafe($property)."'");
   $total=count($properties);
   if ($total) {
    for($i=0;$i<$total;$i++) {
	 if($properties[$i]['INV']) {
		sg($properties[$i]['TARGET_OBJECT'].'.'.$properties[$i]['TARGET_PROPERTY'], (int)!$value);
	 } else { 
		sg($properties[$i]['TARGET_OBJECT'].'.'.$properties[$i]['TARGET_PROPERTY'], $value);
	 }
	 $properties[$i]['UPDATED']=date('Y-m-d H:i:s');
	 SQLUpdate('obj_prptransfer', $properties[$i]);
    }
   }
 }
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
  SQLExec('DROP TABLE IF EXISTS obj_prptransfer');
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall($data = '') {
/*
obj_prptransfer - 
*/
  $data = <<<EOD
 obj_prptransfer: ID int(10) unsigned NOT NULL auto_increment
 obj_prptransfer: TITLE varchar(100) NOT NULL DEFAULT ''
 obj_prptransfer: TARGET_OBJECT varchar(255) NOT NULL DEFAULT ''
 obj_prptransfer: TARGET_PROPERTY varchar(255) NOT NULL DEFAULT ''
 obj_prptransfer: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
 obj_prptransfer: LINKED_PROPERTY varchar(100) NOT NULL DEFAULT ''
 obj_prptransfer: INV int(1) NOT NULL DEFAULT 0
 obj_prptransfer: UPDATED datetime
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgRGVjIDI4LCAyMDE3IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
