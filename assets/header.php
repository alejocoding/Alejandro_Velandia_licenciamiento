
<header class="bg-white border-bottom shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center py-3">
            <?php
            $total = $con->prepare("SELECT sum(valor) FROM licencias");
            $total->execute();
            $total = $total->fetchColumn();
            ?>
            <div class="card bg-success text-white px-3 py-2">
                <strong>Total Ganado:</strong> $<?php echo $total ?>
            </div>
            <button class="btn btn-outline-danger">Cerrar sesión</button>
        </div>
    </header>
