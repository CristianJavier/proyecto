<?php

class ComputadoraController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }
    public function addAction()
    {

      $form=new ComputadoraForm();
      if($this->request->ispost())
       {
          if($form->isvalid($_POST))
          {
              $ip=$this->request->getpost("ip");
              $nombre=$this->request->getpost("nombrepc");
              echo "$ip";
              $data = ServComputadora::insert($ip,$nombre)->toArray();
              
        
         
             /* $computadora=new ServComputadora();
              $computadora->ip=$ip;
              $computadora->nombrepc=$nombre;
              $computadora->save();*/
          }
       }
       $this->view->form=$form;
      
      /*$computadora = $this->request->getJAsonRawBody();
      $data = ServComputadora::insert($computadora->ip,$computadora->nombrepc,$ip,$nombre)->toArray();
      $this->response->setContentType('application/json','UTF-8');
      $this->response->setContent(json_encode($data[0]));
      $this->response->send(); */   
    }
    public function gridAction()
    {
      
    }
    public function jasonAction()
    {
        $this->view->disable();
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];//id del objeto
        $sord = $_GET['sord'];//
        if(!$sidx)$sidx=1;
        $resultado=ServComputadora::find();
        $count=count($resultado);
        if($count>0 & $limit)
        {
           $total_pages=ceil($count/$limit);
        }
        else
        {
            $total_pages=0;
        }
        if($page>$total_pages)$page=$total_pages;
        $star=$page*$limit-$limit;
        if($star<0)$star=0;
        $SQL="SELECT pk_computadora,ip,nombrepc,agregar_usuario,modificar_usuario,eliminar_usuario,agregar_fecha,modificar_fecha,eliminar_fecha FROM ServComputadora ORDER BY $sidx $sord LIMIT $star,$limit";
        $data=$this->modelsManager->executeQuery($SQL)->toArray();
        $resultado=array();
        //esto no se toca
        $resultaod['page']=$page;
        $resultado['total']=$total_pages;
        $resultado['records']=$count;
        //hasta aqui
        $computadora= new ServComputadora();
        $i=0;
        //print_r($data);
        foreach ($data as $propiedad => $valor) 
        {
            foreach ($valor as $p => $v) 
            {
               $computadora->$p=$v; 

            }
            $resultado['rows'][$i]['id']=$computadora->pk_computadora;
            $resultado['rows'][$i]['cell']= array($computadora->pk_computadora,$computadora->ip,$computadora->nombrepc,$computadora->agregar_usuario,$computadora->modificar_usuario,$computadora->eliminar_usuario,$computadora->agregar_fecha,$computadora->modificar_fecha,$computadora->eliminar_fecha);
            $i++;
        }
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($resultado));
        $this->response->send();
    }

}

