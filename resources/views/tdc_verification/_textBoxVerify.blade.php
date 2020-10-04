<div class="pt-4">
    <div class="card bg-light mt-2">

                            <form method="POST" action="{{ route('tdc_verification.show') }}">
                                @csrf
                                <div class="form-group">
                                    <textarea class="w-100" name="tdcs" id="tdcs" pattern="\d"
                                              title="ingresa los numeros de tarjeta"
                                              rows="10" placeholder="Enter card numbers"
                                              required></textarea>
                                    <button type="submit" class="btn btn-outline-primary">Registrar</button>
                                </div>
                            </form>
                        </div>

    </div>
</div>
