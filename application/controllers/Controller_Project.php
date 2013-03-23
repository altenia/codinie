<?php 
defined('APP_PATH') or die('No direct script access.');

require_once FRAMEWORK_PATH . 'LayoutController.php';
require_once CLASSES_PATH . 'TemplateManager.php';
require_once CLASSES_PATH . 'ProjectManager_Xml.php';
require_once CLASSES_PATH . 'DataStructure_Serializer.php';
require_once CLASSES_PATH . 'CodeGen_PhpTemplate.php';

/**
 * The main controller than handles the index
 *
 * @author Young Suk Ahn
 */
class Controller_Project extends LayoutController {
    
	const DS_INTROSPECTOR_PREFIX = 'Ds_Introspector_';
    const MEMBER_SEPARATOR = '_';

	/**
	 * Constructur. Calls the parent's constructor
	 */
    public function __construct() {
        parent::__construct();
    }

	// Called by the framework
	public function beforeAction()
	{
		parent::beforeAction();
		session_start();
		$this->view->title = "Project";
	}

	/**
	 * The index action
	 */
    public function index()
    {
        $project_manager = new ProjectManager_Xml();
		
		$content = View::create('project_index');
		$content->projects = $project_manager->get_list();
		$this->view->content = $content;
		$this->renderView();
    }
	
	/**
	 * The index action
	 */
    public function form()
    {
		$project_manager = ProjectManager::instance();
		$project_details = null;
		$error_fields = null;
        if ($this->isMethodPost()) {
			// Creating a new project
			$project_details = $this->get_project_details_from_post($_POST);
			//print_r($project_details); die();
			
			$error_fields = $project_manager->validate($project_details);
			
			if ($project_manager->id_exists('project')) {
				$error_fields[id] = 'ID already exists';
			}

			if (empty($error_fields)) {
				$project_id = $project_details['id'];
				$project_manager->save($project_details);
				
				$is_new = to_bool($this->getRequestParam('is_new'));
				$action = "Updated";
				if ($is_new) {
					$action = "Created";
				}
				$this->get_logger()->logInfo('Project [' . $project_id . '] updated.');
			
				$this->redirect(
						route_url($this, 'Project', 'work_on', array('prjid' => $project_id))
					);
			}
        }
		
		$content = View::create('project_form');
		if ($this->isMethodGet()) {
			$project_id = ifndef('prjid', $_GET, null);
			
			if (!empty($project_id)) {
				$project_details = $project_manager->get($project_id);
				$content->is_new = false;
			} else {
				$project_details = $project_manager->empty_project_details();
				$content->is_new = true;
			}
		}
		
		// If there was an error $error_fields will be set
		$content->error_fields = $error_fields;
		$content->project_details = $project_details;

		$content->ref_ds_types = $this->get_ds_types();
		$content->templates = TemplateManager::instance()->get_list();
		$this->view->content = $content;
		$this->renderView();

    }

	/**
	 * The lists projects
	 */
    public function work_on()
    {
        $curr_project = $this->load_project();
		//print_r($curr_project);die();
		if (!empty($curr_project)) {
			
			$_SESSION['project'] = $curr_project;
		}
		$this->connect_ds();
    }
	
    public function connect_ds()
    {
		$project_manager = ProjectManager::instance();
		
		$curr_project = $this->get_current_project();
		$breadcrumb = array( array(route_url($this, 'Project', 'index'), 'Project')
			, array(null, $curr_project['name']) );
		View::set_global('breadcrumb', $breadcrumb);
		
        if ($this->isMethodGet()) {
			$content = View::create('project_main');
			
			$content->ref_ds_types = $this->get_ds_types();
			$content->project_details = $curr_project;
			$this->view->content = $content;
			$this->renderView();
        } else if ($this->isMethodPost()) {
			$this->display_tables();
        }
    }
	
	public function display_tables()
	{
		$conn_details = $this->retrieve_conn_details($_POST);
		
		$curr_project = $this->get_current_project();
		$breadcrumb = array( array(route_url($this, 'Project', 'index'), 'Project')
			, array(null, $curr_project['name'])  );
		View::set_global('breadcrumb', $breadcrumb);
		
		if (!empty($conn_details['ds_type'])) {
			$content = View::create('project_main');
			$content->setModel('title', 'Display Table');

			$content->conn_details = $conn_details;
			$content->project_details = $curr_project;
			$content->ref_ds_types = $this->get_ds_types();

			$ds_introspector = $this->get_ds_introspector($conn_details);
      
			$table_pattern = $_POST['table_pattern'];
			$tables = $ds_introspector->get_table_list($table_pattern);
			$content->tables = $tables;
			
			//print_r($tables); die();
			
			$this->view->content = $content;
			$this->renderView();
		} else {
			die('No Type selected');
		}
	}
	
	/**
	 * action that generates code
	 */
	public function generate_code()
    {
		$table = $_POST['tables'];
		$curr_project = $this->get_current_project();
		$breadcrumb = array( array(route_url($this, 'Project', 'index'), 'Project')
			, array(route_url($this, 'Project', 'work_on', array('prjid'=>$curr_project['id'])), $curr_project['name'])
			, array(null, $table)   );
		View::set_global('breadcrumb', $breadcrumb);

		$conn_details = $this->retrieve_conn_details($_POST);

		$content = View::create('project_codegen');
		$content->table_name = $table;
		
		$ds_introspector = $this->get_ds_introspector($conn_details);
		$schema = $ds_introspector->get_schema($table);
		
		$serializer = new DataStructure_Serializer_Xml();
		$content->schema_xml = $serializer->serialize($schema);

		$code_generator = new CodeGen_PhpTemplate(CODE_TEMPLATE_PATH);

		$generated_code = array();
		foreach($curr_project['active-templates'] as $active_template => $active) {
			if ($active) {
				$template_details = TemplateManager::instance()->get($active_template);
				$generated_code[$active_template] 
					= $code_generator->generate($template_details, $schema, $curr_project);
				
				$this->get_logger()->logInfo('Project [' . $curr_project['id'] . ']: Code generated with template [' . $active_template . '].');
			}
		}
		$content->generated_code = $generated_code;
		$this->view->content = $content;
		$this->renderView();
    }
  	
	/**
	 * Returns a list of data source types
	 */
	private function get_ds_types()
	{
		return get_files(CLASSES_PATH . self::DS_INTROSPECTOR_PREFIX, '.php', '*');
	}

	/**
	 * Retreives data source connection details from request
	 */
	private function retrieve_conn_details(&$request)
	{
		$conn_details = array();
		$conn_details['ds_type'] = ifndef('project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'type', $request);
		$conn_details['url'] = ifndef('project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'url', $request, 'localhost');
		$conn_details['username'] = ifndef('project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'username', $request, 'test');
		$conn_details['password'] = ifndef('password', $request, '');
		$conn_details['db_name'] = ifndef('project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'db_name', $request, 'test');
		return $conn_details;
	}
	
	private function get_ds_introspector($conn_details)
	{
		$dbInstrospectorClassName =  self::DS_INTROSPECTOR_PREFIX . $conn_details['ds_type'];

		require_once CLASSES_PATH . $dbInstrospectorClassName . '.php';

		$ds_introspector = new $dbInstrospectorClassName();
		$ds_introspector->create_connection($conn_details['url'], $conn_details['username'], $conn_details['password'], $conn_details['db_name']);
		return $ds_introspector;
	}
	
	/**
	 * Returns project details from session, empty is no project is in session
	 */
	private function get_current_project()
	{
		$curr_project = $_SESSION['project'];
		
		if (empty($curr_project)) {
			// fill with empty details
			$curr_project = $project_manager->empty_project_details();
			$curr_project['name'] = 'New';
		}
		return $curr_project;
	}
	
	/**
	 * Loads project details from Project Manager
	 */
	private function load_project()
	{
		$project_id = ifndef('prjid', $_GET, null);
		
		if (!empty($project_id)) {
			$project_manager = ProjectManager::instance();
			return $project_manager->get($project_id);
		}
		return null;
	}
	
	/**
	 * loads project detail from HTML form
	 *
	 * @return array project details
	 */
	public function get_project_details_from_post($post)
	{
		$project_details = array();
		$project_details['id'] = $post['project' . self::MEMBER_SEPARATOR . 'id'];
		$project_details['name'] = $post['project' . self::MEMBER_SEPARATOR . 'name'];
		$project_details['owner'] = $post['project' . self::MEMBER_SEPARATOR . 'owner'];
		$project_details['language'] = $post['project' . self::MEMBER_SEPARATOR . 'language'];
		$project_details['codegen-dest'] = $post['project' . self::MEMBER_SEPARATOR . 'codegen-dest'];
		$project_details['description'] = $post['project' . self::MEMBER_SEPARATOR . 'description'];
		$project_details['description'] = trim($project_details['description']);
		$project_details['data-source']['type'] = $post['project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'type'];
		$project_details['data-source']['url'] = $post['project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'url'];
		$project_details['data-source']['username'] = $post['project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'username'];
		$project_details['data-source']['db_name'] = $post['project' . self::MEMBER_SEPARATOR . 'data-source' . self::MEMBER_SEPARATOR . 'db_name'];

		if (array_key_exists('project' . self::MEMBER_SEPARATOR . 'active-template', $post)) {
			$project_details['active-templates'] = array();
			foreach($post['project' . self::MEMBER_SEPARATOR . 'active-template'] as $template_id){
				$project_details['active-templates'][$template_id] = true;
			}
		}
		
		$params = trim($post['project' . self::MEMBER_SEPARATOR . 'params']);
		if (!empty($params)) {
			$param_arr = explode("\n", $params);
			foreach ($param_arr as $param_entry) {
				$param_key_val = explode('=', $param_entry, 2);
				$project_details['params'][$param_key_val[0]] = $param_key_val[1];
			}
		}
		
		return $project_details;
	}
	
	private function get_logger()
	{
		$project_log = new KLogger(PROJECTS_PATH . 'codini.log', KLogger::INFO);
		return $project_log;
	}
}
