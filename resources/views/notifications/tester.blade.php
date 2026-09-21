@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-3xl px-4 py-8">

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <!-- Header -->

        <div class="border-b border-gray-200 px-6 py-5">

            <h1 class="text-lg font-semibold text-gray-900">
                Notification Tester
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Pilih stock signal untuk mengirim notification test ke akun Anda.
            </p>

        </div>


        <!-- Form -->

        <form
            id="notificationTestForm"
            method="POST"
            action="{{ route('notification.tester.send') }}"
            class="p-6"
        >

            @csrf


            <div>

                <label
                    for="stock_signal_id"
                    class="block text-sm font-medium text-gray-700"
                >
                    Stock Signal
                </label>


                <select
                    id="stock_signal_id"
                    name="stock_signal_id"
                    required
                    class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

                    <option value="">
                        -- Pilih Stock Signal --
                    </option>


                    @foreach($signals as $signal)

                        <option value="{{ $signal->id }}">

                            {{ $signal->stock_code }}

                            -
                            {{ $signal->stock?->stock_name }}

                            -
                            {{ $signal->signal }}

                            @if($signal->signal_strength)
                                ({{ $signal->signal_strength }})
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>


            <!-- Preview -->

            <div
                id="signalPreview"
                class="mt-5 hidden rounded-lg border border-blue-200 bg-blue-50 p-4"
            >

                <p class="text-xs font-medium uppercase text-blue-600">
                    Test Notification
                </p>

                <p
                    id="previewStock"
                    class="mt-1 text-sm font-semibold text-gray-900"
                ></p>

                <p
                    id="previewSignal"
                    class="mt-1 text-sm text-gray-700"
                ></p>

            </div>


            <!-- Button -->

            <div class="mt-6 flex justify-end">

                <button
                    type="submit"
                    id="sendButton"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Kirim Test Notification
                </button>

            </div>


            <!-- Result -->

            <div
                id="testResult"
                class="mt-4 hidden rounded-lg p-4 text-sm"
            ></div>

        </form>

    </div>

</div>


<script>

    const form = document.getElementById('notificationTestForm');

    const select = document.getElementById('stock_signal_id');

    const preview = document.getElementById('signalPreview');

    const previewStock = document.getElementById('previewStock');

    const previewSignal = document.getElementById('previewSignal');

    const button = document.getElementById('sendButton');

    const result = document.getElementById('testResult');


    select.addEventListener('change', function () {

        const option = this.options[this.selectedIndex];

        if (!this.value) {

            preview.classList.add('hidden');

            return;

        }


        const text = option.text.trim();

        const parts = text.split('-');


        previewStock.textContent = parts[0]?.trim() ?? '';

        previewSignal.textContent = 'Signal: ' + (parts[2]?.trim() ?? '');

        preview.classList.remove('hidden');

    });


    form.addEventListener('submit', async function (event) {

        event.preventDefault();


        button.disabled = true;

        button.textContent = 'Mengirim...';

        result.classList.add('hidden');


        try {

            const response = await fetch(
                form.action,
                {
                    method: 'POST',

                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('input[name="_token"]')
                            .value
                    },

                    body: new FormData(form)
                }
            );


            const data = await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ?? 'Gagal mengirim notification.'
                );

            }


            result.textContent = data.message;

            result.className =
                'mt-4 rounded-lg bg-green-50 p-4 text-sm text-green-700';

            result.classList.remove('hidden');


        } catch (error) {

            result.textContent = error.message;

            result.className =
                'mt-4 rounded-lg bg-red-50 p-4 text-sm text-red-700';

            result.classList.remove('hidden');

        } finally {

            button.disabled = false;

            button.textContent = 'Kirim Test Notification';

        }

    });

</script>

@endsection
