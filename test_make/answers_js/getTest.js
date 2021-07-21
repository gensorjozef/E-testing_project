var key = document.getElementById("test_code").value
test(key);

function getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
    };
}

var cnt = [];
var allQA = [];
var j = 0;

function test(key) {

    fetch("endpoints/getTest.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({key: key})
    }).then((response) => response.json())
        .then((data) => {

            if(data.length == 0){

                document.getElementById("reset-div").style.display = "none";
                return;
            }
            class cell {
                constructor(id, title, x, y, width, height, chosen) {
                    this._id = id;
                    this._title = title;
                    this._x = x;
                    this._y = y;
                    this._width = width;
                    this._height = height;
                    this._chosen = chosen;
                    this._pairedCell = null;
                }

                get pairedCell() {
                    return this._pairedCell;
                }

                set pairedCell(value) {
                    this._pairedCell = value;
                }

                get id() {
                    return this._id;
                }

                set id(value) {
                    this._id = value;
                }

                get title() {
                    return this._title;
                }

                set title(value) {
                    this._title = value;
                }

                get x() {
                    return this._x;
                }

                set x(value) {
                    this._x = value;
                }

                get y() {
                    return this._y;
                }

                set y(value) {
                    this._y = value;
                }

                get width() {
                    return this._width;
                }

                set width(value) {
                    this._width = value;
                }

                get height() {
                    return this._height;
                }

                set height(value) {
                    this._height = value;
                }

                get chosen() {
                    return this._chosen;
                }

                set chosen(value) {
                    this._chosen = value;
                }
            }


            var myCanvas = document.getElementById("myCanvas")
            for (let i = 0; i < data.length; i++) {
                allQA.push({
                    goodAns: null,
                    maxAns: null,
                    pairs: "",
                    id: data[i]["id"],
                    shufle: ""
                })

                var questions_data = data[i]["questions"].split(",")
                var asnwers_data = data[i]["answers"].split(",")
                questions_data.pop();
                asnwers_data.pop();
                var height = questions_data.length * 100 + 100;
                var lbl = document.createElement("label")
                var div1 = document.createElement("div")
                lbl.setAttribute("id", "lbl" + (i + 1))
                lbl.setAttribute("for", "canvas" + (i + 1))
                div1.appendChild(lbl)
                myCanvas.appendChild(div1)
                document.getElementById("lbl" + (i + 1)).innerText = "Sparujte spravne otazky a odpovede (" + data[i]["points"] + "b)";
                var ltpc = document.createElement("canvas");
                var div2 = document.createElement("div")
                div2.setAttribute("class", "text-center")
                div2.appendChild(ltpc)
                ltpc.setAttribute("height", height)
                ltpc.setAttribute("width", "800")
                ltpc.setAttribute("id", "canvas" + (i + 1))
                myCanvas.appendChild(div2);
                var ltpc = document.getElementById("canvas" + (i + 1));
                var questions = [];
                var asnwers = [];
                initQuestions(questions, asnwers)
                drawPaint(questions, asnwers);
                initListeners(questions, asnwers);
                function initQuestions(questions, asnwers) {
                    let tmp = [];
                    for (var x = 0; x < questions_data.length; x++) {
                        const quest = new cell(x + 1, questions_data[x], 50, x * 100 + 50, 200, 70, false);
                        const aswer = new cell(x + 1, asnwers_data[x], 550, x * 100 + 50, 200, 70, false)
                        questions.push(quest)
                        asnwers.push(aswer)
                        tmp.push({id: x + 1, title: asnwers_data[x], x: 550, y: x * 100 + 50})
                    }
                    tmp = shuffle(tmp);
                    for (var x = 0; x < questions.length; x++) {
                        asnwers[x].x = tmp[x].x;
                        asnwers[x].y = tmp[x].y;
                    }
                }

                function wrapText(context, text, x, y, maxWidth, lineHeight) {
                    var words = text.split(' ');
                    var line = '';
                    for (var n = 0; n < words.length; n++) {
                        var testLine = line + words[n] + ' ';
                        var metrics = context.measureText(testLine);
                        var testWidth = metrics.width;
                        if (testWidth > maxWidth && n > 0) {
                            context.fillText(line, x, y);
                            line = words[n] + ' ';
                            y += lineHeight;
                        } else {
                            line = testLine;
                        }
                    }
                    context.fillText(line, x, y);
                }

                function shuffle(array) {
                    var currentIndex = array.length, temporaryValue, randomIndex;
                    while (0 !== currentIndex) {
                        randomIndex = Math.floor(Math.random() * currentIndex);
                        currentIndex -= 1;

                        temporaryValue = array[currentIndex];
                        array[currentIndex] = array[randomIndex];
                        array[randomIndex] = temporaryValue;
                    }
                    return array;
                }

                function drawPaint(questions, asnwers) {
                    var context = ltpc.getContext("2d");
                    context.font = "20pt sans-serif";
                    context.beginPath();
                    context.fillText("Otázky", 100, 30)
                    context.fillText("Odpovede", 580, 30)
                    context.font = "10pt sans-serif";
                    for (var x = 0; x < questions.length; x++) {
                        wrapText(context, questions[x].title, questions[x].x + 5, questions[x].y + 15, questions[x].width, 15)
                        context.rect(questions[x].x, questions[x].y, questions[x].width, questions[x].height)
                    }
                    for (var x = 0; x < asnwers.length; x++) {
                        wrapText(context, asnwers[x].title, asnwers[x].x + 5, asnwers[x].y + 15, asnwers[x].width, 15)
                        context.rect(asnwers[x].x, asnwers[x].y, asnwers[x].width, asnwers[x].height)
                    }
                    context.closePath();
                    context.stroke();
                }

                var question;

                function initListeners(questions, asnwers) {
                    var ltpc = document.getElementById("canvas" + (i + 1))
                    var context = ltpc.getContext("2d");
                    var count = 0;
                    if (count <= questions.length) {
                        ltpc.addEventListener("mousedown", (s) => {
                            var qx = parseInt(getMousePos(ltpc, s).x);
                            var qy = parseInt(getMousePos(ltpc, s).y);
                            for (var x = 0; x < questions.length; x++) {
                                if (qx >= questions[x].x && qx <= questions[x].x + questions[x].width && qy >= questions[x].y && qy <= questions[x].y + questions[x].height && questions[x].chosen === false) {
                                    context.beginPath()
                                    context.moveTo(questions[x].x + questions[x].width, questions[x].y + questions[x].height / 2)
                                    question = questions[x];
                                    ltpc.addEventListener("mouseup", (e) => {
                                        var ax = parseInt(getMousePos(ltpc, e).x);
                                        var ay = parseInt(getMousePos(ltpc, e).y);
                                        for (var y = 0; y < asnwers.length; y++) {
                                            if (ax >= asnwers[y].x && ax <= asnwers[y].x + asnwers[y].width && ay >= asnwers[y].y && ay <= asnwers[y].y + asnwers[y].height && asnwers[y].chosen === false && question.chosen === false) {
                                                context.lineTo(asnwers[y].x, asnwers[y].y + asnwers[y].height / 2);
                                                context.closePath();
                                                context.stroke();
                                                question.chosen = true;
                                                asnwers[y].chosen = true;
                                                asnwers[y].pairedCell = question.id;
                                                count++;
                                                if (count === questions.length) {
                                                    var id = ltpc.id
                                                    id = id.charAt(id.length - 1)
                                                    j++;
                                                    id = data[id - 1]["id"]
                                                    let points = 0;
                                                    var result = new Map;
                                                    var shufle = [];
                                                    for (let i = 0; i < asnwers.length; i++) {
                                                        if (asnwers[i].id === asnwers[i].pairedCell) {
                                                            points++;
                                                        }
                                                        result.set(asnwers[i].id, asnwers[i].pairedCell)
                                                        shufle.push({y: asnwers[i].y})
                                                    }
                                                    cnt.push({
                                                        goodAns: points,
                                                        maxAns: asnwers.length,
                                                        pairs: result,
                                                        id: id,
                                                        shufle: shufle
                                                    })
                                                }
                                            }
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            }
        })
}

function rst() {
    var myCanvas = document.getElementById("myCanvas")
    while (myCanvas.firstChild) {
        myCanvas.firstChild.remove();
    }
    cnt = [];
    allQA = [];
    j = 0;
    test(key)
}

function mapToJson(map) {
    return JSON.stringify([...map]);
}

function submit() {
    var key = document.getElementById("student_test").value;
        for (let k = 0; k < allQA.length; k++) {
            for (let i = 0; i < cnt.length; i++) {
                if(allQA[k]["id"] == cnt[i]["id"]){
                    allQA[k] = cnt[i];
                    allQA[k]["pairs"] =  mapToJson(cnt[i]["pairs"])
                }
            }
        }
        fetch("endpoints/submitTest.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({result: allQA, test_key: key})
        }).then((response) => response.json())
            .then((data) => {

            })
}










