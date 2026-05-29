<x-filament-panels::page>
    <form wire:submit="save">
        <div class="space-y-6">
            <x-filament::section heading="WhatsApp">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Driver</label>
                        <select wire:model="data.whatsapp_driver" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="twilio">Twilio</option>
                            <option value="meta">Meta (WhatsApp Business API)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Twilio SID</label>
                        <input wire:model="data.twilio_sid" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Twilio Token</label>
                        <input wire:model="data.twilio_token" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Twilio From</label>
                        <input wire:model="data.twilio_whatsapp_from" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Meta WhatsApp Token</label>
                        <input wire:model="data.meta_whatsapp_token" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Meta Phone Number ID</label>
                        <input wire:model="data.meta_phone_number_id" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section heading="Gemini AI">
                <div>
                    <label class="block text-sm font-medium">API Key</label>
                    <input wire:model="data.gemini_api_key" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </x-filament::section>

            <x-filament::section heading="Correo">
                <div>
                    <label class="block text-sm font-medium">From Address</label>
                    <input wire:model="data.mail_from_address" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </x-filament::section>

            <div>
                <x-filament::button type="submit" color="success">Guardar configuración</x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
