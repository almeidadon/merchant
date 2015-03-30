<body>
<?php if($status == '0') { ?>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
<div class="nav-brand"><img src="<?php echo base_url();?>/assets_todo/images/shmart_transparent.png" /></div>
    <div class="row m-n">
        <div class="col-md-6 col-md-offset-3 m-t-lg">
            <section class="panel">
                <form action="index.html" class="panel-body">
                    <header class="panel-heading text-center">
                        <section class="panel bg-success lter no-borders">
                            <div class="panel-body text-center">
                                <a class="pull-right" href="#"></a>
                                <div class="text-center padder m-t">
                                    <span class="h2"><i class="icon-ok-sign text-muted"></i>Success!</span>
                                </div>
                                <span class="h5 text-center">Your transaction has been successfuly processed !</span>
                            </div>
                            <footer class="panel-footer lt">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <strong class="text-muted block">Merchant Ref ID</strong>
                                        <strong><span><?php echo $merchant_refID; ?></span></strong>
                                    </div>
                                    <div class="col-xs-4">
										
                                    </div>
                                    <div class="col-xs-4">

                                    </div>
                                </div>
                            </footer>
                        </section>
                    </header>
                    <form action="index.html" class="panel-body">
                        <section class="panel">
                            <header class="panel-heading">
                                <span class="badge bg-info pull-right"><i class="icon-paper-clip"></i></span>Your Transaction Summary</header>
                            <section class="panel-body">
                                <article class="media">
                                    <div class="pull-left thumb-sm">
                      <span class="icon-stack">
                        <i class="icon-circle text-success icon-stack-base"></i>
                        <i class="icon-inr icon-light"></i>
                      </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="pull-right media-xs text-center text-muted">
                                            <strong class="h4">Rs <?php echo $total_amount; ?></strong><br>

                                        </div>
                                        <a href="#" class="h4">Amount of transaction</a>
                                        <small class="block m-t-sm">Total amount of transaction</small>
                                    </div>
                                </article>
                                <div class="line pull-in"></div>
                                <article class="media">
                                    <div class="pull-left thumb-sm">
                      <span class="icon-stack">
                        <i class="icon-circle text-danger icon-stack-base"></i>
                        <i class="icon-file icon-light"></i>
                      </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="pull-right media-xs text-center text-muted">
                                            <strong class="h4"><?php echo $merchant_refID; ?></strong><br>

                                        </div>
                                        <a href="#" class="h4">Merchant Ref ID</a>
                                        <small class="block m-t-sm">Use this for future reference of the transaction</small>
                                    </div>
                                </article>
                                <div class="line pull-in"></div>
                                <article class="media">
                                    <div class="pull-left thumb-sm">
                      <span class="icon-stack">
                        <i class="icon-circle text-warning icon-stack-base"></i>
                        <i class="icon-question icon-light"></i>
                      </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="pull-right media-xs text-center text-muted">

                                            <strong class="h5">wecare@shmart.in</strong><br>

                                        </div>
                                        <a href="#" class="h4 text-success">Queries ?</a>

                                    </div>
                                </article>
                            </section>
                        </section>

                    </form>
            </section>
<?php } else { ?>
            <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
                <div class="nav-brand"><img src="<?php echo base_url();?>assets_todo/images/shmart_transparent.png" /></div>
                <div class="row m-n">
                    <div class="col-md-6 col-md-offset-3 m-t-lg">
                        <section class="panel">
                            <form action="index.html" class="panel-body">
                                <header class="panel-heading text-center">
                                    <section class="panel bg-danger lter no-borders">
                                        <div class="panel-body text-center">
                                            <a class="pull-right" href="#"></a>
                                            <div class="text-center padder m-t">
                                                <span class="h2"><i class="icon-remove text-muted"></i>Failed!</span>
                                            </div>
                                            <span class="h5 text-center">Your transaction has been failed ! <br /></span>
                                            <span class="h5 text-center">Reason : <b><?php echo $status_msg; ?> </b></span>
                                        </div>
                                        <footer class="panel-footer lt">
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <strong class="text-muted block">Merchant Ref ID</strong>
                                                    <strong><span><?php echo $merchant_refID; ?></span></strong>
                                                </div>
                                                <div class="col-xs-4">

                                                </div>
                                                <div class="col-xs-4">

                                                </div>
                                            </div>
                                        </footer>
                                    </section>
                                </header>
                                <form action="index.html" class="panel-body">
                                    <section class="panel">
                                        <header class="panel-heading">
                                            <span class="badge bg-info pull-right"><i class="icon-paper-clip"></i></span>Your Transaction Summary</header>
                                        <section class="panel-body">
                                            <article class="media">
                                                <div class="pull-left thumb-sm">
                      <span class="icon-stack">
                        <i class="icon-circle text-success icon-stack-base"></i>
                        <i class="icon-inr icon-light"></i>
                      </span>
                                                </div>
                                                <div class="media-body">
                                                    <div class="pull-right media-xs text-center text-muted">
                                                        <strong class="h4">Rs <?php echo $total_amount; ?></strong><br>

                                                    </div>
                                                    <li class="h4">Amount of transaction</li>
                                                    <small class="block m-t-sm">Total amount of transaction</small>
                                                </div>
                                            </article>
                                            <div class="line pull-in"></div>
                                            <article class="media">
                                                <div class="pull-left thumb-sm">
                      <span class="icon-stack">
                        <i class="icon-circle text-danger icon-stack-base"></i>
                        <i class="icon-file icon-light"></i>
                      </span>
                                                </div>
                                                <div class="media-body">
                                                    <div class="pull-right media-xs text-center text-muted">
                                                        <strong class="h4"><?php echo $merchant_refID; ?></strong><br>

                                                    </div>
                                                    <li class="h4">Merchant Ref ID</li>
                                                    <small class="block m-t-sm">Use this for future reference of the transaction</small>
                                                </div>
                                            </article>
                                            <div class="line pull-in"></div>
                                            <article class="media">
                                                <div class="pull-left thumb-sm">
                      <span class="icon-stack">
                        <i class="icon-circle text-warning icon-stack-base"></i>
                        <i class="icon-question icon-light"></i>
                      </span>
                                                </div>
                                                <div class="media-body">
                                                    <div class="pull-right media-xs text-center text-muted">

                                                        <strong class="h5">wecare@shmart.in</strong><br>

                                                    </div>
                                                    <li class="h4 text-success">Queries ?</li>

                                                </div>
                                            </article>
                                        </section>
                                    </section>

                                </form>
                        </section>

                        <!--                    <div class="form-group">-->
<!--                        <label class="control-label">Email</label>-->
<!--                        <input type="email" placeholder="test@example.com" class="form-control">-->
<!--                    </div>-->
<!--                    <div class="form-group">-->
<!--                        <label class="control-label">Password</label>-->
<!--                        <input type="password" id="inputPassword" placeholder="Password" class="form-control">-->
<!--                    </div>-->
<!--                    <div class="checkbox">-->
<!--                        <label>-->
<!--                            <input type="checkbox"> Keep me logged in-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <a href="#" class="pull-right m-t-xs"><small>Forgot password?</small></a>-->
<!--                    <button type="submit" class="btn btn-info">Sign in</button>-->
<!--                    <div class="line line-dashed"></div>-->
<!--                    <a href="#" class="btn btn-facebook btn-block m-b-sm"><i class="icon-facebook pull-left"></i>Sign in with Facebook</a>-->
<!--                    <a href="#" class="btn btn-twitter btn-block"><i class="icon-twitter pull-left"></i>Sign in with Twitter</a>-->
<!--                    <div class="line line-dashed"></div>-->
<!--                    <p class="text-muted text-center"><small>Do not have an account?</small></p>-->
<!--                    <a href="signup.html" class="btn btn-white btn-block">Create an account</a>-->
<?php } ?>
                </form>
            </section>
        </div>
    </div>
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder clearfix">
        <p>
            <small>www.shmart.in<br>&copy; 2014</small>
        </p>
    </div>
</footer>
