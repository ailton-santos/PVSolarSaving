<?php require_once __DIR__.'/lib/models/calculate.php'; ?>
<?php include 'header.php'; ?>

    <div id="dom-target" style="display:none;">
        <?php
            echo htmlspecialchars($percentSaved);
        ?>
    </div>

    <header>
        <div class="StripeBackground">
            <div class="stripe s0"></div>
            <div class="stripe s2 pattern"></div>
        </div>
        <div class="StripeBackground accelerated">
            <div class="stripe s1"></div>
            <div class="stripe s3"></div>
            <div class="stripe s4"></div>
        </div>
        <div class="container">
            <h1 class="title">
                Calculate Your Savings
                <span class="second-line">Learn how to start</span>
            </h1>
            <div class="full">
                <div class="half">
                    <div class="plan">
                        <h2 class="plan-title">Results <i class="icon-pie-chart"></i></h2>
                        <div class="plan-content">
                            <h4><?php echo $location; ?></h4>
                            <div class="ct-chart ct-golden-section"></div>
                        </div>
                    </div>
                </div>
                <div class="half">
                    <div class="plan">
                        <h2 class="plan-title">Savings by the numbers <i class="icon-wallet"></i></h2>
                        <div class="plan-content">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Estimated Yearly Savings</h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo round($percentSaved, 2); ?>%
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Estimated Payback Period</h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $payback; ?> years
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="download" class="download bg-primary text-center">
        <div class="container">
            <div class="full">
                <div class="half">
                    <div class="plan">
                        <h2 class="plan-title">Sunlight <i class="icon-globe"></i></h2>
                        <div class="plan-content">
                            <img src="img/sun-hands-op.jpg" alt="Hands around the sun">
                        </div>
                    </div>
                </div>
                <div class="half">
                    <div class="plan">
                        <h2 class="plan-title">Solar Panels <i class="icon-grid"></i></h2>
                        <div class="plan-content">
                            <img src="img/panels.jpg" alt="Solar Panels">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>