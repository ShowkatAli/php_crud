<?php

class Paginator {
 
    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;
 
    public function __construct( $conn, $query ) {
  
        $this->_conn = $conn;
        $this->_query = $query;
        try{
            $rs= $this->_conn->prepare( $this->_query );
            $rs->execute();
        $this->_total = $rs->rowCount();
        }
        catch (Exception $e){

        }

    }

    public function getData( $limit = 10, $page = 1 ) {
        
        $this->_limit   = $limit;
        $this->_page    = $page;
        if ( $this->_limit == 'all' ) {
            $query      = $this->_query;

        } else {
            $query = $this->_query.' LIMIT '.($this->_page - 1) * $this->_limit.', '.$this->_limit;
        }

        $query      = $this->_conn->prepare($query);
        $query->execute();
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $key => $value):
            $results[] = $value;
        endforeach;

    
        $result         = new stdClass();
        $result->page   = $this->_page;
        $result->limit  = $this->_limit;
        $result->total  = $this->_total;      
        $result->data   = isset($results)?$results:[];
    
        return $result;
    }

    public function createLinks( $links, $list_class ) {
        if ( $this->_limit == 'all' ) {
            return '';
        }
     
        $last       = ceil( $this->_total / $this->_limit );
     
        $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
     
        $html       = '<ul class="' . $list_class . '">';
        
        $class      = ( $this->_page == 1 ) ? "disabled" : "";
        $search_param = (isset($_GET['q']) && strlen($_GET['q'])>0)?'q='.$_GET['q'].'&':'';
        $html       .= '<li class="mr-1 ' . $class . '"><a href="?'.$search_param.'limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';
     
        if ( $start > 1 ) {
            $html   .= '<li class="mr-1"><a href="?'.$search_param.'limit=' . $this->_limit . '&page=1">1</a></li> ';
            $html   .= '<li class="disabled"><span>...</span></li>';
        }
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class  = ( $this->_page == $i ) ? "active" : "";
            $html   .= '<li class="mr-1 ' . $class . '"><a href="?'.$search_param.'limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
        }
     
        if ( $end < $last ) {
            $html   .= '<li class="mr-1 disabled"><span>...</span></li>';
            $html   .= '<li class="mr-1 ><a href="?'.$search_param.'limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>';
        }
     
        $class      = ( $this->_page == $last ) ? "disabled" : "";
        $html       .= '<li class="mr-1 ' . $class . '"><a href="?'.$search_param.'limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';
     
        $html       .= '</ul>';
     
        return $html;
    }
}


