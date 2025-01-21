<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Services\UserService;

class EditUser extends Component
{
    public User $user;
    public $name, $email;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];
    }

    public function update(UserService $userService)
    {
        // Validar los datos del formulario
        $this->validate();

        // Actualizar el usuario usando el servicio
        $userService->update($this->user, [
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Mostrar un mensaje de éxito y redirigir
        session()->flash('message', 'Usuario actualizado correctamente.');
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
