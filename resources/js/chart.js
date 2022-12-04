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
    var myChart = echarts.init(
        document.getElementById("myChart"),
        localStorage.getItem("darkMode") === "true" ? "dark" : "light"
    );
    window.onresize = function () {
        myChart.resize();
    };
    Livewire.on("chartDataUpdated", (dataObj) => {
        var series = [];
        for (const data of dataObj.datasets ?? []) {
            series.push({
                name: `${data.label}`.replaceAll("&nbsp;", " "),
                type: "line",
                data: data.data,
                smooth: true,
                symbol: function (_value, params) {
                    const color = params.color;
                    const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="-50 -50 100 100">
                    <circle cx="0" cy="0" r="10" fill="${color}" />
                    <circle cx="0" cy="0" r="50" fill="transparent" />
                  </svg>`;
                    return `image://data:image/svg+xml;base64,${btoa(svg)}`;
                },
                symbolSize: 30,
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
                top: 80,
                right: 10,
                left: 50,
                bottom: 50,
            },
            xAxis: {
                data: dataObj.labels ?? [],
                splitLine: {
                    show: true,
                },
                type: "category",
                boundaryGap: false,
                axisLabel: {
                    interval: 0,
                    rotate: 45,
                    hideOverlap: true,
                    rich: {},
                    formatter: function (value) {
                        return "{value|" + value + "}";
                    },
                },
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
                type: "value",
            },
            series: series,
        };
        myChart.setOption(option, {
            replaceMerge: ["xAxis", "yAxis", "series"],
        });

        let metaDescription = "";
        if (dataObj.title) {
            document.title = dataObj.title;
            metaDescription = `Analyse ${dataObj.title} in JoSAA Counselling using past 10 years data.`;
        } else {
            document.title = "Trends | JoSAA Analysis";
            metaDescription = `Analyse the trends of JoSAA cut-offs using past 10 years data.`;
        }
        document
            .querySelector('meta[name="description"]')
            .setAttribute("content", metaDescription);
    });
    Livewire.emit("updateChartData");
});
