<?php
class Subfolder extends CI_Controller {

    /*
     * /blog/subfolder
     */
	public function index()
	{
		$this->load->view('blog/subfolder');
	}
}
?>