
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

               

                <form method="POST" action="/v1/tests/analyze" class="card-body">
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

                        <input name="title" type="text" class="form-control" autofocus  value="sumra chat">
                        <br>
                        <input name="subscriber_ids[]" type="text" class="form-control" autofocus  value="00000000-1000-1000-1000-000000000000">
                
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
                        <option value="1">Voluptatibus doloribus cupiditate cum laborum dolo...</option>
                        <option value="2">Error labore aspernatur id aperiam dolores qui. Ve...</option>
                        <option value="3">Natus harum temporibus doloribus ratione amet. Ear...</option>
                    </select>
                       
                        
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>

                        <input name="product_url" type="text" class="form-control" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="https://discord.gg/DUMwfyckKy">
                        
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