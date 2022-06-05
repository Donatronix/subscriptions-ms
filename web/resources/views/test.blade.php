
<div class="h-100vh light-mode default-sidebar" style="width: 100%">
    <div
        style="background-image: url('../../images/edms-back.jpeg'); background-position: center;background-size: cover;"
    >
        <div class="page">
            <div class="page-single" style="display: flex !important;">
                <div class="container">
                    <div class="row">
                        <div class="col mx-auto">
                            <div class="row justify-content-center">
                                <div class="col-md-7 col-lg-5">
                                    <div class="card card-group mb-0">
                                        <div class="card p-4">

               

                <form method="POST" action="/tests/analyze" class="card-body">
                    {{-- @csrf --}}
                    <div
                    class="text-center mb-6"
                >
                <h3>Waiting List message Test</h3>
 
                    <br>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>

                        <input name="platform" type="text" class="form-control" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="sumra chat">
                
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                        <select
                        class="form-control"
                        name="message_id"
                        required
                    >
                    <option disabled selected>--Select Message--</option>
                        <option value="05b6b31d-0fef-30b2-a943-1b2ec2c4b53c">Voluptatibus doloribus cupiditate cum laborum dolo...</option>
                        <option value="0cbd10b4-f502-3232-8297-13079d8b0246">Error labore aspernatur id aperiam dolores qui. Ve...</option>
                        <option value="2103a07a-7236-37be-bbc6-8127c09610bf">Natus harum temporibus doloribus ratione amet. Ear...</option>
                    </select>
                       
                        
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>

                        <input name="url" type="text" class="form-control" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="https://discord.gg/DUMwfyckKy">
                        
                    </div>
                       
                    </div>

                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="submit" class="btn btn-lg btn-danger btn-block" id="submit_log">
                                    <i
                                        class="fe fe-arrow-right"
                                    ></i>
                                    send
                                </button>
                                <div class="d-flex justify-content-center my-3" id="spinner">
                                </div>
                              
                          
                            <div
                            class="col-12 d-flex justify-content-between mt-2 align-items-center"
                        >
                            <p
                                class=" box-shadow-0 px-0 mr-2 mb-0"
                            >
                            
                            </p>
                          
                            </div>
                            
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script>
    document.getElementById("submit_log").addEventListener("click", myFunction);
    
    function myFunction() {
        document.getElementById("spinner").innerHTML = '<i class="fa fa-spinner fa-spin" style="font-size:24px; color:red"></i>'
    }  
</script>
<script>

    function showPWD(){
        var x = document.getElementById("password");
        var eye = '<i class="fa fa-eye fa-md eye" id="eye" aria-hidden="true" onclick="showPWD()" style="position:absolute; right:10px; top:12px; cursor: pointer; z-index:999;"></i>';
        var eyeClose = '<i class="fa fa-eye-slash fa-md eyeClose" id="eyeClose" aria-hidden="true" onclick="showPWD()" style="position:absolute; right:10px; top:12px; cursor: pointer; z-index:999;"></i>';
        var div = document.getElementById("div");
        if(x.type === "password"){
            x.type = "text";
            div.innerHTML = eyeClose;
            x.focus();
            
        }else{
            x.type = "password";
            div.innerHTML = eye;
            x.focus();
            
        }
    }
</script>