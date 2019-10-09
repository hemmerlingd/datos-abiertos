const duerme = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
}

function muestra_datos(listainicial)
{
	var row=0;
	var tarjeta="";
	var img="";
	var titulo="";
	//var tarjeta='<table width="100%" style="font-family:Arial;" cellpadding="0" cellspacing="0">';
	document.getElementById("resultados").innerHTML='';
	console.log(listainicial);
	tarjeta='<div class="wpb_row vc_row  mk-fullwidth-false  attched-false  vc_row-fluid  js-master-row  mk-in-viewport">';
	for(i=0;i<=listainicial.length-1;i++)
	{
			img='<img src="https://areadc.cordoba.gob.ar/wp-content/uploads/sites/32/2019/10/landing_transicin-abierta_icono-contrataciones.png" alt="">';
			titulo='<p class="mk-box-icon-2-content">'+(listainicial[i][0])+'</p>';
		
		if (row <=5){
			
			tarjeta+='<div style="" class="vc_col-sm-2 wpb_column column_container  _ height-full"><div id="mk-icon-box-14" class="mk-box-icon-2  box-align-center "><div class="mk-box-icon-2-image" style="width:128px;">';
			tarjeta+='<a href="'+listainicial[i][2]+'" target="_blank">';
			tarjeta+= img;	
			tarjeta+='</a>';
			tarjeta+='</div><h3 class="mk-box-icon-2-title"></h3>';
			tarjeta+= titulo;
			tarjeta+='</div></div>';
			
			row++;
		}else{
			tarjeta+='</div><div class="wpb_row vc_row  mk-fullwidth-false  attched-false  vc_row-fluid  js-master-row  mk-in-viewport">';
			row=0;
		}
	}
	document.getElementById("resultados").innerHTML+=tarjeta;

}

function filtrocategoria(arr) {

	if (document.getElementById("categorias").value!="")
	{
		if(arr[1]!=null)
		{
			if(arr[1]==document.getElementById("categorias").value)
			{
				return true;
			}
		}
	}else{
		return true;	
	}
}

function filtra_datos()
{
	data_filter = lista;
	if (document.getElementById("categorias").value!="")
	{
		data_filter = data_filter.filter(filtrocategoria);
	}
	
	document.getElementById("loading").style.display="block";
	duerme(1000).then(() => {
    document.getElementById("loading").style.display="none";	
    muestra_datos(data_filter); 
	})

	
}