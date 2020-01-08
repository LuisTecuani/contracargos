@csrf
<div class="form-group">
    <textarea class="w-100" name="autorizaciones" id="autorizaciones" pattern="\d"
              title="escribe autorizaciones seguidas de coma e inmediatamente los ultimos 4 digitos de la tarjeta.
               e.g. 223344,4455"
              rows="10" placeholder="Autorización, terminación tarjeta"
              required></textarea>
    <button type="submit" class="btn btn-outline-primary">Registrar</button>
</div>
