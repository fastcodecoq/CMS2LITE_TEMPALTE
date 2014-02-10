<div %vista:contacto.php%>
   <div class="block-gray full" style="padding:3px 0;"></div>

<section class="bloker wrapp" >

          <br>          
      <div class="wrapp">
          <h2 class="tb tlc grayy normal">Peticiones, Quejas y Reclamos</h2>
          <br>          
          <br>          
          <br>          
          <p class="grayy normal pp tlc toCenter"  %editable%>Para nosotros es muy importante brindar un buen servicio y, sobre todo que, usted como usuario se encuentre siempre satisfecho con el servicio que prestamos. Si usted tiene una inquietud no dude en hacernosla saber, llenando el siguiente formulario.</p>

      </div>
<br>
               <br>
               <br>
               <br>
      <div class="toCenter">
                   <div class="sede-sele">   <span>Sede</span>&nbsp; &nbsp;<div class="sedy" data-cmd="sede" data-action><span class="sedee" data-cmd="sede" data-action></span>&nbsp;<span class="caret" style="margin-top:.5em" data-cmd="sede" data-action></span></div> </div>
                   <br>
                   <br>
          <form method="POST" id="cont" data-callback="send" data-forms data-live-validate>
            <label>
           <span>Nombre (requerido)</span>
            <input type="text" name="name" placeholder="Escriba su nombre" data-type="only-text" data-require/>
            </label>
            <br>
               <br>
            <label>
           <span>Nic (requerido) <i class="info-ask" title="Este número lo encuentra en el recibo de energía" >?</i></span>
            <input type="text" name="nic" placeholder="Escriba el número de contrato" data-type="number" data-require/>
            </label>
            <br>
               <br>
            <label>
           <span>Número de cédula (requerido)</span>
            <input type="text" name="cc" placeholder="Escriba su número de documento" data-type="number" data-require/>
            </label>
            <br>
               <br>
            <label>
           <span>Correo electrónico</span>
           <small>Podremos notificarte a tu correo el estado de tu solicitud</small>           
            <input type="text" name="mail" placeholder="sucuentade@correo.com" data-type="email" data-require="no"/>
            </label>
            <br>
               <br>
            <label>
           <span>Solicitud (requerido)</span>
           <small>Escribe una solicitud con un máximo de 200 caracteres</small>
            <textarea placeholder="Escriba aquí su solicitud..." name="msg" data-type="text" data-require></textarea>
            </label>
            <br>
               <br>
            <label>
           <span>Documento anexo</span>
           <small>Si tiene un documento anexo, por favor adjuntarlo</small>
            <input type="file" name="attachment" data-require="no"/>
            </label>
            <br>
               <br>
            <label>
               <input type="submit" value="Enviar" style="width:100%" >
            </label>
          </form>
      </div>
       
</section>

</div>

<script type="text/javascript">
  

  function send(rs){

     window.form = rs[0];

     if(!rs)
       {
        dialog.show("Por favor, corrige los campos resaltados con borde rojo");
        return false;
        }

      rs = rs[0];



      data = new FormData();
      data.append("name", rs.name.value);      
      data.append("nic", rs.nic.value);
      data.append("cc", rs.cc.value);      
      data.append("msg", rs.msg.value);
      data.append("email", rs.mail.value);      
      var sede = (window.localStorage.sede) ? window.localStorage.sede : 0;
      data.append("sede", sede);      

      if(rs.attachment.files[0])
      data.append("file", rs.attachment.files[0]);                


     console.log(data);

     window.verb = (/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,3})+(\.[a-z]{2}\s)?$/g.test(rs.mail.value)) ? "Te hemos enviado un correo (" + rs.mail.value + ") con toda la información relacionada con tu solicitud." : "Por favor guarda los siguientes datos: <br><br> Número de solicitud <b>{{num_pqr}}</b> <br> PIN <b>{{pin}}</b>"


     $.ajax({
          url : "{{theme_path}}/controlers/pqr.php?new",
          data : data,
          processData: false,
          contentType: false,
          type : "POST",
          dataType : "JSON",
          success : sended,
          error : failed
     });

  }


  var sended = function(rs){ 
    console.log(rs); 
    
    if(rs.success === 1) 
      {
        dialog.show("Enhorabuena!, " + window.verb.replace(/\{\{pin\}\}/g, rs.pin).replace(/\{\{num_pqr\}\}/g, rs.num_pqr)); 
        window.form.reset();
      }
    else 
      dialog.show("Por el momento no podemos recibir tu solicitud. Hemos informado de este error al personal encargado y, será solucionado cuanto antes."); 

    }

  var failed = function(fail){ console.log(fail); }
</script>