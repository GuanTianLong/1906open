<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login❤❤❤用户登录</title>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <script src="/jquery.js"></script>
    <script src="/static/bootstrap.min.js"></script>
</head>
<body>

<h3 align="center" style="color:red">❤❤<b style="color:black">某公司用户登录</b>❤❤</h3><hr>

<form class="form-horizontal" role="form" action="{{url('/user/login')}}" method="post">
    <div class="form-group has-success">
        <label class="col-sm-2 control-label" for="inputSuccess">
            账号:
        </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="inputSuccess" name="user_name" placeholder="请输入手机号&Email...">
        </div>
    </div>
    <div class="form-group has-warning">
        <label class="col-sm-2 control-label" for="inputWarning">
            密码:
        </label>
        <div class="col-sm-3">
            <input type="password" class="form-control" id="inputWarning" name="password" placeholder="请输入密码...">
        </div>
    </div>
    <div>
        <label class="col-sm-2 control-label" for="inputError"></label>

        <button class="btn btn-success">登录</button>
    </div>
</form>

</body>
</html>