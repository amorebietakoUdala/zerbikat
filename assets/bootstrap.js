import { startStimulusApp } from '@symfony/stimulus-bridge';
import { table } from '@amorebietakoudala/stimulus-controller-bundle/src/table_controller';
import { locale } from '@amorebietakoudala/stimulus-controller-bundle/src/locale_controller';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

// register any custom, 3rd party controllers here
app.register('table', table );
app.register('locale', locale );
// app.register('some_controller_name', SomeImportedController);
