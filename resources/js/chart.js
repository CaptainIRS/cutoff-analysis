import * as echarts from "echarts/core";
import { LineChart } from "echarts/charts";
import {
    TooltipComponent,
    GridComponent,
    LegendComponent,
    DataZoomComponent,
    AriaComponent,
} from "echarts/components";
import { CanvasRenderer } from "echarts/renderers";

echarts.use([
    TooltipComponent,
    GridComponent,
    LegendComponent,
    DataZoomComponent,
    LineChart,
    CanvasRenderer,
    AriaComponent,
]);

window.addEventListener("load", () => {
    var myChart = echarts.init(document.getElementById("myChart"));
    window.onresize = function () {
        myChart.resize();
    };
    Livewire.on("chartDataUpdated", (dataObj) => {
        console.log("Updating chart data");
        console.log(dataObj);
        var series = [];
        for (const data of dataObj.datasets ?? []) {
            series.push({
                name: `${data.label}`.replaceAll("&nbsp;", " "),
                type: "line",
                data: data.data,
                smooth: true,
            });
        }
        var option = {
            aria: {
                show: true,
            },
            tooltip: {
                renderMode: "html",
                extraCssText: "max-width: 200px; white-space: normal;",
                position: function (pos, _params, _dom, _rect, size) {
                    var obj = {};
                    if (pos[0] < size.viewSize[0] / 2) {
                        obj["left"] =
                            pos[0] + 220 < size.viewSize[0]
                                ? pos[0] + 20
                                : size.viewSize[0] - 220;
                    } else {
                        obj["right"] =
                            size.viewSize[0] - pos[0] + 220 < size.viewSize[0]
                                ? size.viewSize[0] - pos[0] + 20
                                : 20;
                    }
                    if (pos[1] < size.viewSize[1] / 2) {
                        obj["top"] =
                            pos[1] + 100 < size.viewSize[1]
                                ? pos[1] + 20
                                : size.viewSize[1] - 100;
                    } else {
                        obj["bottom"] =
                            size.viewSize[1] - pos[1] + 100 < size.viewSize[1]
                                ? size.viewSize[1] - pos[1] + 20
                                : 20;
                    }
                    return obj;
                },
            },
            legend: {
                show: true,
                type: "scroll",
                orient: "horizontal",
                height: 100,
                top: 0,
                textStyle: {
                    width: 200,
                    overflow: "break",
                },
            },
            grid: {
                top: 100,
                right: 30,
                left: 50,
                bottom: 20,
            },
            xAxis: {
                data: dataObj.labels ?? [],
                splitLine: {
                    show: true,
                },
                type: "category",
                boundaryGap: false,
            },
            dataZoom: [
                {
                    type: "inside",
                    filterMode: "none",
                    yAxisIndex: [0],
                },
            ],
            yAxis: {
                inverse: true,
                min: (value) => (value.min - 20 < 0 ? 0 : value.min - 20),
            },
            series: series,
        };
        myChart.setOption(option, {
            replaceMerge: ["xAxis", "yAxis", "series"],
        });

        if (dataObj.title) {
            document.title = dataObj.title + " | Trends | JoSAA Analysis";
        } else {
            document.title = "Trends | JoSAA Analysis";
        }
    });
    Livewire.emit("updateChartData");
});
