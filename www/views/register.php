<h1>Registration page</h1>

<div class="container">
    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Firstname</label>
            <input name="firstname" type="text" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Lastname</label>
            <input name="lastname" type="text" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control">
        </div>
        <div class="col-md-12">
            <label class="form-label">Username</label>
            <input name="username" type="text" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Password</label>
            <input name="pass" type="password" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Password confirm</label>
            <input name="passConf" name="pass" type="password" class="form-control">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
