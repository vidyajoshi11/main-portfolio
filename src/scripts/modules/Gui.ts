/** @format */

import { Pane } from 'tweakpane';
import * as EssentialsPlugin from '@tweakpane/plugin-essentials';
import Common from './Common';

export type Options = Record<string, string | number | boolean | Color>;

type Color = {
    r: number;
    g: number;
    b: number;
};

export default class Gui {
    private readonly options?: Options;
    private readonly fps?: EssentialsPlugin.FpsGraphBladeApi;

    /**
     * @constructor
     */
    constructor(options?: Options) {
        this.options = options;

        if (!this.options) return;

        const pane = new Pane();
        pane.registerPlugin(EssentialsPlugin);

        pane.addBinding(this.options, 'timeScale', {
            min: 0.0,
            max: 4.0,
        }).on('change', (v) => {
            if (typeof v.value === 'number') {
                Common.timeScale = v.value;
            }
        });

        this.fps = pane.addBlade({
            view: 'fpsgraph',
        }) as EssentialsPlugin.FpsGraphBladeApi;
    }

    /**
     * # Update FPS
     */
    private updateFps() {
        if (this.fps) {
            this.fps.begin();
            this.fps.end();
        }
    }

    /**
     * # rAF update
     */
    update() {
        this.updateFps();
    }
}
