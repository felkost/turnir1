var masNode = [];
masNode.push(new Obj("Alpha_1")); masNode.push(new Obj("Beta_2")); masNode.push(new Obj("Gamma_3"));
//-------------------------------
function Obj(keyName) {   //конструктор об’єкта
    this.key = keyName
}

var $ = go.GraphObject.make;
var myDiagram =
    $(go.Diagram, "myDiagramDiv",
        {
            initialContentAlignment: go.Spot.Center, // center Diagram contents
            "undoManager.isEnabled": true // enable Ctrl-Z to undo and Ctrl-Y to redo
        });

var myModel = $(go.Model);
// in the model data, each node is represented by a JavaScript object: key = keyName


myModel.nodeDataArray = masNode; //масив з іншого скрипта; присвоювати тільки масив весь!
    myDiagram.model = myModel;

