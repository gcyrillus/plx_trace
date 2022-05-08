let fileobj;
let index=0;
let upAction ='ignore';
let root=location.protocol + '//' + location.host;//+ location.pathname+'../../';
let subpath = location.pathname;
let uppath= subpath.replace('plugin.php', '') +'../..';
root = root + uppath;
let prefX='../../';
for (let radioAction of document.querySelectorAll('input[name="action"]')) {
	  radioAction.addEventListener("change", function() {
      upAction = radioAction.value;
		});   
	}
function upload_file(e,ipt,section,dir,upAction) {
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    ajax_file_upload(fileobj,ipt,section,dir,upAction);
}  
function file_explorer(ipt,section,dir,upAction) {
    document.getElementById(ipt).click();
    document.getElementById(ipt).onchange = function() {
        fileobj = document.getElementById(ipt).files[0];
        ajax_file_upload(fileobj,ipt,section,dir,upAction);
    };
}  
function ajax_file_upload(file_obj,ipt,section,dir,upAction) {
    let container= document.getElementById(section);    
    index++;
    let name= 'infos'+index;
    let classInfo='.'+name;
    let infos = document.createElement("p");
    infos.setAttribute('class',name);
    container.appendChild(infos);
    if(file_obj != undefined) {
        let form_data = new FormData();                  
        form_data.append('file', file_obj);
        let xhttp = new XMLHttpRequest();
        let upUrl= '../../plugins/plx_trace/ajax.php?dir='+dir+'&do='+upAction;
        xhttp.open("POST", upUrl , true);
        xhttp.onload = function(event) {
            output = document.querySelector(classInfo);
            if (xhttp.status == 200) {
                let msg='';
                let msgwarning='';
                
                if((this.responseText.trim() == file_exists) ||(this.responseText.trim() == fileExt_required)) {
                    msg= ' <b class="green" style="color:tomato;background:pink; padding:0 0.5em" > ! </b>' ;
                    msgwarning='  color:tomato;text-align:center;font-weight:bold; ';
                } 
                else if((this.responseText.trim() !=fileExt_required) || (this.responseText.trim() != file_exists)) { 
                    ipt.trim();
                    let selectToUpdate='[name=select'+ipt+']';
                    let selgpx=document.querySelector(selectToUpdate); 
                    let optionLabels = Array.from(selgpx.options).map((opt) => opt.text);                    
                        if(optionLabels.includes(this.responseText.trim())) {
                            msg= ' <b class="green"> ! </b><b style="order:-1;"> Fichier mis à jour: </b>' ;
                        }
                        else {                
                             msg=' <b class="green">&check;</b> <em style="color:tomato">Fichier àjouté à la liste.</em>';
                            let newOpt = document.createElement('option');
                            newOpt.textContent =`${this.responseText}`;
                            let newAttr = `plugins/plx_trace/gpx/${dir.trim()}/${this.responseText.trim()}`;
                            newOpt.setAttribute('value',newAttr);
                            selgpx.appendChild(newOpt);  
                        }
            }  else { 
                 msg='<b  style="color:tomato;background:pink;" class="green">!</b>';
                 msgwarning='  color:tomato;text-align:center;font-weight:bold; ';             
            }  
                        
                newinfos=`    <p  style="display:flex;gap:0.25em;${msgwarning}"> ${this.responseText}   ${msg}</p> `;                 
                output.innerHTML =  newinfos;
            } else {
                output.innerHTML = fileError +' ' + xhttp.status + " est survenue en tentant de telecharger le fichier.";
            }
        }
 
        xhttp.send(form_data);
    }
}
for (let gpxFile of document.querySelectorAll('#drop_file_area .results select')) {
	  gpxFile.addEventListener("change", function() {
		let tracegpxFile= gpxFile.value;
        tracegpxFile = tracegpxFile.replace(/^(?:\.\.\/)+/, "");
		tracegpxFile.trim();
        let mydata='.'+this.getAttribute('data-code');

        let code= document.querySelector(mydata);
        let myPrvBox= '#'+this.getAttribute('data-code');
        let myPreviewId=  document.querySelector(myPrvBox);
        // reset preview

        let codeTPL=`<div  data-gpxFile="${tracegpxFile}">&nbsp;</div>
`;           
        myPreviewId.innerHTML= codeTPL;
  

        
        

            if (document.querySelector('input[name="preview"]').checked && tracegpxFile !=='' ) {
                displayMap(myPreviewId, prefX+tracegpxFile);                    
            }

     
        if (tracegpxFile ==='' ) { codeTPL='';       } 
        code.innerHTML=codeTPL;	
       
		});   
	}