<?php
/**
 * Created by PhpStorm.
 * User: KhuselaM
 * Date: 2019-03-01
 * Time: 19:52
 */
//include('connect.php');

namespace core;

class Paginator{

    private $request_url;
    public $per_page;
    /* used to determine the last page, last page will be total_rows / per page e.g 10 rows found / 5
    rows per page = last page = 2 */
    private $total_rows_found = false;
    //the current page number
    public $page =  false;
    private $last_page = "";
    private $pagination_links = ""; //html pagination links rendered on the view
    private $rows_found = "";
    private $limit = false;
    //ajax based or nomal paginator (normal paginator trigger page refresh when you click on pagination links)
    private $paginator_type = false;
  

    public function __construct($rows_found,$per_page=15,$paginator_type = '', $results = [])
    {
        $this->paginator_type = $paginator_type == false ? "normal" : 'ajax';
        $this->rows_found = $rows_found;
        $this->total_rows_found = $rows_found;
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->request_url = $this->get_request_path();
        $this->per_page = $per_page;
        $this->last_page = ceil($this->total_rows_found / $this->per_page);
        $this->results = $results;
    }

    //=====================================================================
    public function create_next(){
        if ($this->last_page != 1) {

            if ($this->page > 1 && $this->page != $this->last_page) {
                $next_page = $this->page + 1; //current page + 1
                $request_url = $this->get_request_path().'?';

                $this->pagination_links .= $this->create_html_for_pagination_links($next_page,$request_url,"Next");
            }
        }
    }
    //=====================================================================
    public function create_previous(){
        if ($this->page > 1) {
            //Show 'Previous' only if page number is greater than 1,
            $previous_page = $this->page - 1;
            $request_url = $this->get_request_path().'?';
            $this->pagination_links = $this->create_html_for_pagination_links($previous_page,$request_url,"Previous");
        }
    }
    //=====================================================================
    public function create_html_for_pagination_links($page_number,$request_url,$html_text,$is_link_active =''){

        if($this->paginator_type == 'ajax'){

            return "<li class='page-item $is_link_active' >
                        <a class = 'page-link'  onclick ='paginate(\"$request_url&page=$page_number\")'>$html_text</a>
                    </li>";

        }else{
            $ref = $request_url."&page=".$page_number;
            return "<li class='page-item $is_link_active' >
                        <a class = 'page-link'  href='$ref' class = 'page-link'>$html_text</a>
                    </li>";

        }

    }
    //===============================================================================
    public function create_pagination_links(){

        $request_query_strings = $this->get_query_strings();

        unset($request_query_strings['page']); //removes current page from query strings

        for ($page = 1; $page <= $this->last_page; $page++) {

            $request_url = $this->get_request_path().'?'.http_build_query($request_query_strings);

            //class: to show link as active or in active
            $active = "";
            if ($this->page == $page) {
                $active = "active";
            }
            
            $this->pagination_links .= $this->create_html_for_pagination_links($page,$request_url,$page,$active);
        }
    }
    //===============================================================================
    public function get_pagination_links(){

        $this->create_previous();
        $this->create_pagination_links();
        $this->create_next();

        return $this->pagination_links;
    }
    //===============================================================================
    public function get_request_path(){

         $request_url = $_SERVER['REQUEST_URI'];

         return parse_url($request_url)['path'];

    }
    //===============================================================================
    public function get_request_query_strings(){

        $request_url = $_SERVER['REQUEST_URI'];
        $request_url = parse_url($request_url);

        return isset($request_url['query']) ? $request_url['query'] : "";
        
    }
    //===============================================================================
    public function get_limit(){
        return $this->limit;
    }
    //===============================================================================
    public function get_page_nu(){
       return $this->page_nu;
    }
    //===============================================================================
    public function get_query_strings(){

        parse_str($_SERVER['QUERY_STRING'],$query_strings);
        return $query_strings;

    }

    
}
