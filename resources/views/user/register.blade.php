<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register❤❤❤用户注册</title>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <script src="/jquery.js"></script>
    <script src="/static/bootstrap.min.js"></script>
</head>
<body>

<h3 align="center" style="color:red">❤❤<b style="color:black">某公司用户注册</b>❤❤</h3><hr>

<form class="form-horizontal" role="form" action="{{url('/user/register_do')}}" method="post" enctype="multipart/form-data">
    <div class="form-group has-success">
        <label class="col-sm-2 control-label" for="inputSuccess">
            公司名称:
        </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="inputSuccess" name="com_name" placeholder="请输入公司名称...">
        </div>
    </div>
    <div class="form-group has-warning">
        <label class="col-sm-2 control-label" for="inputWarning">
            法人:
        </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="inputWarning" name="com_legal" placeholder="请输入法人...">
        </div>
    </div>
    <div class="form-group has-success">
        <label class="col-sm-2 control-label" for="inputSuccess">
            公司地址:
        </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="inputSuccess" name="com_address" placeholder="请输入公司地址...">
        </div>
    </div>
    <div class="form-group has-warning">
        <label class="col-sm-2 control-label" for="inputWarning">
            营业执照照片:
        </label>
        <div class="col-sm-3">
            <input type="file" class="form-control" id="inputWarning" name="com_logo">
        </div>
    </div>
    <div class="form-group has-success">
        <label class="col-sm-2 control-label" for="inputSuccess">
            联系电话:
        </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="inputSuccess" name="com_mobile" placeholder="请输入手机号...">
        </div>
    </div>
    <div class="form-group has-warning">
        <label class="col-sm-2 control-label" for="inputWarning">
            邮箱:
        </label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="inputWarning" name="com_email" placeholder="请输入邮箱...">
        </div>
    </div>
    <div class="form-group has-success">
        <label class="col-sm-2 control-label" for="inputSuccess">
            密码:
        </label>
        <div class="col-sm-3">
            <input type="password" class="form-control" id="inputSuccess" name="com_password" placeholder="请输入密码...">
        </div>
    </div>
    <div class="form-group has-success">
        <label class="col-sm-2 control-label" for="inputSuccess">
            确认密码:
        </label>
        <div class="col-sm-3">
            <input type="password" class="form-control" id="inputSuccess" name="confirm_pass" placeholder="请输入密码...">
        </div>
    </div>
    <div>
        <label class="col-sm-2 control-label" for="inputError"></label>

        <button class="btn btn-success">注册</button>
    </div>
</form>

</body>
</html>