<select name="city_id" class="form-control js-city-select" id="city_id" placeholder="انتخاب شهر">
    <option value="">انتخاب کنید</option>
    <?php foreach ($cities as $city): ?>
        <option value="<?= $city['city_id'] ?>" <?= ($selected_city_id == $city['city_id']) ? 'selected' : '' ?>>
            <?= $city['city_name'] ?>
        </option>
    <?php endforeach; ?>
</select>