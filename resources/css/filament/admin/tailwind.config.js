import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Clusters/Administrasi/**/*.php',
        './resources/views/filament/clusters/administrasi/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-curator/resources/**/*.blade.php',
        './vendor/awcodes/filament-table-repeater/resources/**/*.blade.php'
    ],
}
