/* import { startStimulusApp } from '@symfony/stimulus-bundle';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
 */

/* import { startStimulusApp } from "@symfony/stimulus-bundle";
import "@symfony/ux-chartjs";

startStimulusApp().then((app) => {
  console.log("Stimulus et ChartJS sont prêts");
}); */
import { startStimulusApp } from "@symfony/stimulus-bundle";
import ChartController from "@symfony/ux-chartjs";

startStimulusApp().then((app) => {
  app.register("chart", ChartController);
  console.log("Stimulus et ChartJS sont prêts");
});
