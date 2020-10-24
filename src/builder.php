<?php
namespace webrium\foxql;
// use webrium\foxql\query;

class builder {


  public function makeValueString($args,$table=false)
  {

    if (count($args)==3) {
      if (strpos($args[1],'()')===false) {
        $str = "$this->table.`".$args[0]."` ".$args[1]." :".$args[0];
      }
      else {
        $args[1] = str_replace('()',"(:".$args[0].")",$args[1]);
        $str = "$this->table.`".$args[0]."` ".$args[1];
      }
    }
    elseif (count($args)==2) {
      $str = "$this->table.`".$args[0]."` = :".$args[0];
    }
    return $str;
  }


  public function addToWhereQuery($op,$str)
  {
    $index = $this->SqlStractur("WHERE");

    if (! isset($this->query_array[$index])) {
      $this->query_array[$index]='';
      $this->query_array[$index].= 'where ';
    }
    else {
      $this->query_array[$index] .= " $op ";
    }

    $this->query_array[$index] .= $str;
  }

  public function addToSelectFields($fields)
  {
    $index = $this->SqlStractur("FIELDS");

    if (! isset($this->query_array[$index])) {
      $this->query_array[$index]='';
    }

    $this->query_array[$index]=$fields;
  }

  public function makeQueryStr($type='select')
  {
    if ($type=='select') {
      return $this->getSelectQuery();
    }
    // return $str;
    // return $this->query_array;
  }

  public function getSelectQuery()
  {
    $fields = $this->query_array[$this->SqlStractur('FIELDS')]??false;

    if ($fields==false) {
      $fields = '*';
    }

    $str = "select $fields from $this->table ";

    foreach ($this->query_array??[] as $key => $value) {
      $str.=$value;
    }
    return $str;
  }

  public function explodeFieldName($name)
  {
    $table = $this->table;

    if (strpos($name,'.')>0) {
      $arr   = explode('.',$name);
      $table = "`".$arr[0]."`";
      $name  =  $arr[1];
    }

    return['table'=>$table,'field'=>$name];
  }

  public function getFieldStr($name)
  {
    $arr = $this->explodeFieldName($name);
    return $arr['table'].".`".$arr['field']."`";
  }


  public function SqlStractur($key=null)
  {
    $arr=[
      'SELECT'        =>1,
      'FIELDS'        =>2,
      'ALL'           =>3,
      'DISTINCT '     =>4,
      'DISTINCTROW'   =>5,
      'HIGH_PRIORITY' =>6,
      'STRAIGHT_JOIN' =>7,
      'FROM'          =>8,
      'JOIN'          =>9,
      'WHERE'         =>10,
      'GROUP_BY'      =>12,
      'HAVING'        =>13,
      'ORDER_BY'      =>14,
      'LIMIT'         =>15,
      'OFFSET'        =>16,
      'UNION'         =>17
    ];
    if ($key==null) {
      return $arr;
    }
    else {
      return $arr[$key];
    }
  }
}
