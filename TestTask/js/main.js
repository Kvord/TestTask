// 
// Вход администратора
// 
function login(){
    var login = $("#login").val(); //Получение данных
    var pwd = $("#loginPwd").val();
    $.ajax({ // Метод используется для фоновой обработки запросов
        type: "POST",
        method: "POST",
        url: "/controller.php",
        dataType: "json",
        data: {'args': [ login, pwd ], 'func': "login"}
        }).done(function(data) {
            if (data['success']) {
                $('#loginBox').hide();
                $('#userBox').show();
                $('#user').html(data['displayName']);
                location.reload(); // Перезагруска страницы
            } else {
                alert("Неверный логин или пароль!"); // Обратная связь
            }
    });
}

// 
// Выход из логина
// 
function logout() {
    $.ajax({
        type: "POST",
        method: "POST",
        url: "/controller.php",
        dataType : 'json',
        data: {'func': "logout"}
        }).done(function(data) {
            if (data['success']) {
                alert("Вы вышли");
                $('#userBox').hide();
                $('#loginBox').show();
                location.reload();
            } else {
                alert("Не удалось выйти!");
            }
    });
}

// 
// Создание задачи
// 
function makeTask(){
    var name = $("#name").val();
    var email = $("#email").val();
    var taskmake = $("#taskmake").val();
    $.ajax({
        type: "POST",
        method: "POST",
        url: "/controller.php",
        dataType : "json",
        data: {'args': [ name, email, taskmake ], 'func': "makeTask"}
        }).done(function(data) {
            if (data['success']) {
                alert(data['message'])
                location.reload();
            } else {
                alert(data['message'])
            }
    });
}

// 
// Редактирование задачи администратором
// 
function changeTask(itemId){
    var name = $("#nametable").val();
    var email = $("#emailtable").val();
    var task = $("#tasktable").val();

    $.ajax({
        type: "POST",
        method: "POST",
        url: "/controller.php",
        dataType : "json",
        data: {'args': [ name, email, task, itemId ], 'func': "changeTask"}
        }).done(function(data) {
            if (data['success']) {
                alert("Задача изменена.");
                location.reload();
            } else {
                alert("Ошибка изменения. Необходимо зарегистрироваться!");
            }
    });
}

// 
// Изменение статуса задачи
// 
function setStatus(itemId){
    var status = $("#itemStatus_" + itemId).prop("checked");
    if (status == true) {
        status = 1;
    } else {
        status = 0;
    }
    console.log(status);
    $.ajax({
        type: "POST",
        method: "POST",
        url: "/controller.php",
        dataType : 'json',
        data: {'args': [ itemId, status ], 'func': "setStatus"}
        }).done(function(data) {
            if (data['success']){
                location.reload();
            } else {
                alert("Ошибка установки статуса!");
            }
    });
}

// 
// Раскрытие блока создания задачи
// 
function showBoxMakeTask(){
    if( $("#taskField").css('display') != "block") {
        $("#taskField").show();
    } else {
        $("#taskField").hide();
    }
}

// 
// Раскрытие блока логина
// 
function showLoginField(){
    if( $("#loginField").css('display') != "block") {
        $("#loginField").show();
    } else {
        $("#loginField").hide();
    }
}