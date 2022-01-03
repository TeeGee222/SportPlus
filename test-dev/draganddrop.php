<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://bernardo-castilho.github.io/DragDropTouch/DragDropTouch.js"></script>
    <title>Drag & Drop Test</title>
</head>
<body>
    <div id="dropzone">
        <div id="record-id-23" class="node" draggable="true">Pompes</div>
        <div id="record-id-54" class="node" draggable="true">planche</div>
        <div id="record-id-11" class="node" draggable="true">jumping jack</div>
        <div id="record-id-74" class="node" draggable="true">Squat</div>
        <div id="record-id-83" class="node" draggable="true">burpees</div>
        <div id="record-id-27" class="node" draggable="true">ciseaux</div>
        <div id="record-id-64" class="node" draggable="true">Planche</div>
    </div>
    
    <script>
        var dropzone = document.getElementById("dropzone");
        var nodes = document.getElementsByClassName("node");
        var selectedNode = '';
        var selectedNodePos = 0;

        for (var i = 0; i < nodes.length; i++) {
            nodes[i].addEventListener("mousedown",  (ev) =>{

                for (var i = 0; i < nodes.length; i++) {
                    nodes[i].style.backgroundColor = 'cornsilk';
                    
                }

                document.getElementById(ev.target.id).style.backgroundColor = 'tomato';
                document.getElementById(ev.target.id).style.transition = '0s';
            })
            
            nodes[i].addEventListener("dragstart",  (ev) =>{
                ev.dataTransfer.setData('text', ev.target.id);
                console.log('start');

                selectedNode = document.getElementById(ev.target.id);
                setTimeout(() => {
                    dropzone.removeChild(selectedNode);
                }, 0);
            })
            
        }
        dropzone.addEventListener('dragover', (ev) => {
            ev.preventDefault();
            whereAmI(ev.clientY)
        })
        dropzone.addEventListener('drop', (ev) => {
            ev.preventDefault();
            dropzone.insertBefore(selectedNode, dropzone.children[selectedNodePos]);

            resetNodes();

            setTimeout(() => {
                selectedNode.style.backgroundColor = 'cornsilk';
                selectedNode.style.transition = '2s';
            }, 200);
            
        })
        document.body.addEventListener('touchmove', (ev) => {
            ev.preventDefault();
            }, false); 

        function establishNodePositions() {
            for (var i =0; i < nodes.length;i++){
                var element = document.getElementById(nodes[i]['id']);
                var position = element.getBoundingClientRect();
                var yTop = position.top;            
                var yBottom = position.bottom;
                nodes[i]['yPos'] = yTop + ((yBottom-yTop)/2);
                //console.log(nodes[i]['innerHTML'] + ' is top value of ' + yTop);
                //console.log(nodes[i]['innerHTML'] + ' is center value of ' + yCenter);
                //console.log(nodes[i]['innerHTML'] + ' is bottom value of ' + yBottom);
                //console.log('-----------------')
            }
        }

        function resetNodes() {
            for (var i = 0; i < nodes.length; i++) {
                document.getElementById(nodes[i]['id']).style.marginTop = '0.5em';
                
            }
        }

        function whereAmI(currentYPos) {
            establishNodePositions();
            for (var i  = 0; i < nodes.length; i++) {
                if (nodes[i]['yPos']<currentYPos){
                    var nodeAbove = document.getElementById(nodes[i]['id']);
                    selectedNodePos = i+1;
                }
                else{
                    if(!nodeBelow){
                        var nodeBelow = document.getElementById(nodes[i]['id']);
                    }
                    
                }
                document.getElementById(nodes[i]['id']).value=selectedNodePos;
            }
            if (typeof nodeAbove == 'undefined'){
                selectedNodePos = 0;
            }

            resetNodes();

            if (typeof nodeBelow == 'object'){
                nodeBelow.style.marginTop = '3em';
                nodeBelow.style.transition = '1.8s';
            }
            
            console.log(selectedNodePos);
        }
        

    </script>

    <style>
        #dropzone {
            background-color: black;
            margin: 0.5em;
            padding: 0.4em;
            min-height: 100vh;
            font-size: 1.4em;
        }
        .node{
            background-color: cornsilk;
            margin:0.5em;
            padding: 0.4em;
            border: 3px black solid;
        }
    </style>
</body>
</html>