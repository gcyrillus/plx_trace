let fileobj;
let index=0;
function upload_file(e,ipt,section,dir) {
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    ajax_file_upload(fileobj,ipt,section,dir);
}  
function file_explorer(ipt,section,dir) {
    document.getElementById(ipt).click();
    document.getElementById(ipt).onchange = function() {
        fileobj = document.getElementById(ipt).files[0];
        ajax_file_upload(fileobj,ipt,section,dir);
    };
}  
function ajax_file_upload(file_obj,ipt,section,dir) {
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
        let upUrl= '../../plugins/plx_trace/ajax.php?dir='+dir;
        xhttp.open("POST", upUrl , true);
        xhttp.onload = function(event) {
            output = document.querySelector(classInfo);
            if (xhttp.status == 200) {
                let msg='';
                let msgwarning='';
                if(this.responseText.trim() !='Fichier avec l\'extension gpx requis') { 
                    ipt.trim();
                    let selectToUpdate='[name=select'+ipt+']';
                    let selgpx=document.querySelector(selectToUpdate); 
                    let optionLabels = Array.from(selgpx.options).map((opt) => opt.text);                    
                        if(optionLabels.includes(this.responseText.trim())) {
                            msg= ' <b class="green"> ! </b><b style="order:-1;"> Fichier mis à jour: </b>' ;
                        }
                        else {                
                             msg=' <b class="green">&check;</b> <em style="color:tomato">Le code pour ce fichier est dans la liste.</em>';
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
                output.innerHTML = "Error " + xhttp.status + " occurred when trying to upload your file.";
            }
        }
 
        xhttp.send(form_data);
    }
}
for (let gpxFile of document.querySelectorAll('#drop_file_area .results select')) {
	  gpxFile.addEventListener("change", function() {
		let tracegpxFile= gpxFile.value;
        tracegpxFile = tracegpxFile.replace(/^(?:\.\.\/)+/, "");
			console.log(tracegpxFile);
        let mydata='.'+this.getAttribute('data-code');
        console.log(mydata);
        let code= document.querySelector(mydata);
        let codeTPL=`<script>const gpxFile='${tracegpxFile}';</script>
<div id="myMapGpx"></div>
<plx_trace/>`;        
        code.innerHTML=codeTPL;	  
		});   
	}
