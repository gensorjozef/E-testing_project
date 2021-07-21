if (typeof (EventSource) !== "undefined") {
    var source = new EventSource("sse.php")
    // source.onmessage = function (event) {
    //     document.getElementById("result").innerHTML = event.data
    // }
    source.addEventListener('evt', async (e) => {
        //document.getElementById("result").innerHTML = e.data;
        var students = await getStudents();
        var table = document.getElementById("table");
        students.forEach(student => {
            if(document.getElementById(student["ais_id"]) == null){
                var row = document.createElement("tr");

                var name = document.createElement("td");
                name.appendChild(document.createTextNode(student["name"] + " " + student["surname"]));
                row.appendChild(name);

                var active = document.createElement("td");
                if(student["end_test"] == null){
                    active.appendChild(document.createTextNode("Áno"));
                    active.className = "green";
                }else{
                    active.appendChild(document.createTextNode("Nie"));
                    active.className = "red";
                }
                active.id = student["ais_id"] + "active";
                row.appendChild(active);

                var tab = document.createElement("td");
                tab.id = student["ais_id"];
                tab.appendChild(document.createTextNode("-"));
                row.appendChild(tab);

                table.appendChild(row);
            }
            if(student["end_test"] != null){
                document.getElementById(student["ais_id"]+"active").innerHTML = "Nie";
                document.getElementById(student["ais_id"]+"active").className = "red";
                document.getElementById(student["ais_id"]).innerHTML = "-";
                document.getElementById(student["ais_id"]).className = "";
            }
        });
        var anotherTab = JSON.parse(e.data);
        for(var ais in anotherTab){
            if(anotherTab[ais]){
                document.getElementById(ais).innerHTML = "Áno";
                document.getElementById(ais).className = "red";
            }else{
                document.getElementById(ais).innerHTML = "Nie";
                document.getElementById(ais).className = "green";
            }
        }
    })
} else {
    // Sorry! No server-sent events support..
}

async function getStudents(){
    var testKey = document.getElementById("testKey").innerHTML;
    const request = new Request('./get_students.php', {
        method: 'POST',
        body: JSON.stringify({testKey: testKey})
    });
    const response = await fetch(request);
    const jsonRespone = await response.json();
    return jsonRespone;
}