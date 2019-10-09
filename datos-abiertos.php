<?php
/*
Plugin Name: Datos Abiertos
Plugin URI: https://github.com/ModernizacionMuniCBA/
Description: Este plugin muestra el listado Datos disponibles en Gobierno Abierto [datos_abiertos]
Version: 2.0.0
Author: Ignacio Perlo - David Hemmerling
Author URI: https://github.com/perloignacio
*/


setlocale(LC_ALL,"es_ES");
date_default_timezone_set('America/Argentina/Cordoba');

add_action('plugins_loaded', array('datos_abiertos', 'get_instancia'));

function categorias_sort($a, $b) {
    return strcmp($a, $b);
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}		

class datos_abiertos
{
	public static $instancia = null;
	
	public static function get_instancia() {
		if (null == self::$instancia) {
			self::$instancia = new datos_abiertos();
		} 
		return self::$instancia;
	}
	
	
	private function __construct()
	{
		
		
		add_shortcode('datos_abiertos', array($this, 'render_shortcode_datos_abiertos'));
		
		add_action('wp_enqueue_scripts', array($this, 'cargar_assets_basura'));
		add_action('wp_enqueue_scripts', array($this, 'custom_dequeue'));
		//add_action('init', array($this, 'boton_shortcode_datos_abiertos'));
		//add_filter( 'script_loader_tag', 'cameronjonesweb_add_script_handle', 10, 3 );
		add_action( 'wp_head', 'custom_dequeue', 9999 );
	}
	
	public function cargar_assets_basura()
	{
		

		$urldabiertos_shortcode = $this->cargar_url_basura('/css/shortcode-dabiertos.css');
		 wp_register_style('datos_abiertos_css', $urldabiertos_shortcode);
		 
		 $urlJSdabiertos = $this->cargar_url_basura('/js/cargar-datos.js');
		 
		 wp_register_script('datos_abiertos', $urlJSdabiertos,null,false,false);
		
		global $post;
	    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'datos_abiertos') ) {
			wp_enqueue_style('datos_abiertos_css', $urldabiertos_shortcode);
			wp_enqueue_script('datos_abiertos',$urlJSdabiertos,null,false,false);
		}	
		 
		
		
	}
	private function cargar_url_basura($ruta_archivo)
	{
		return plugins_url($ruta_archivo, __FILE__);
	}
	
	
	
	public function render_shortcode_datos_abiertos($atributos = [], $content = null, $tag = '')
	{
	    
    	//$nombre_transient = 'actividades_disciplina_' . $atr['disciplina'];
		
		$plugin_dir=plugin_dir_url( __FILE__ )."data/";
		$fila = 0;
		$categorias=array();
		$resultados=array();
		
		$datos=str_getcsv(wp_remote_retrieve_body(wp_remote_get($plugin_dir."datos.txt")),"\n");
		
		for($fila=1;$fila<=count($datos)-1;$fila++)
		{	
			
			$obj=explode(";",$datos[$fila]);
			$numero=count($obj)-1;
			
			if ($fila>=1){
					
					for ($c=0; $c < $numero; $c++) {
						if($c==1){
							if(in_array($obj[$c],$categorias)==false)
							{
								if($obj[$c]!=""){
								array_push($categorias,$obj[1]);
								}
								
								//echo $datos[$c] . "<br />\n";
							}
						}
						
					}
					
				}
			array_push($resultados,$obj);
		}
		usort($categorias,"categorias_sort");
		$sc='<div id="buscador">
				<div class="campo">
					<select id="categorias" onchange="javascript:filtra_datos();">
						<option value="">Seleccione la categoria</option>
						';
							foreach($categorias as $categoria)
							{
								$sc.="<option value='".($categoria)."'>".($categoria)."</option>";
							}
						$sc.='
					</select>
				</div>
			</div>';
		$sc.='<div class="resultado__container">
				<div id="loading"><img style="display:none;" src="'.plugin_dir_url( __FILE__ ).'images/loading.gif" /></div>
				<div id="resultados"></div>
			</div>';
		$sc.='<script type="text/javascript">
				var lista;
				var data_filter;
				lista='.json_encode(($resultados)).';';
				$sc.='
				
				</script>';
		return $sc;
		
	}

	/*public function boton_shortcode_datos_abiertos() {
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
			return;

		add_filter("mce_external_plugins", array($this, "registrar_tinymce_plugin")); 
		add_filter('mce_buttons', array($this, 'agregar_boton_tinymce_shortcode_datos_abiertos'));
	}

		public function registrar_tinymce_plugin($plugin_array) {
		$plugin_array['recorridos_button'] = $this->cargar_url_asset('/js/shortcode.js');
	    return $plugin_array;
	}

	public function agregar_boton_tinymce_shortcode_datos_abiertos($buttons) {
	    $buttons[] = "recorridos_button";
	    return $buttons;
	}
	*/
	
	
	
}
?>