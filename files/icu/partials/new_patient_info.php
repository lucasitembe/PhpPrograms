<div class="row mt-3 mb-2">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="bg-brand d-inline-block rounded-2 px-4 text-white text-center" style="margin-bottom: -50px; padding: 12px">
            <b>ICU - <?= isset($form_name) ? $form_name : "SET FORM TYPE";?></b>
            <br>
            <?= $Patient_Name ?> |
            <?= $Gender ?> |
            <?= $age ?> YEARS |
            <?= $Guarantor_Name ?>
        </div>
    </div>
</div>