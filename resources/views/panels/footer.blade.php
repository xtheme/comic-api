<footer class="footer footer-light @if(isset($configData['footerType'])){{$configData['footerClass']}}@endif">
    <div class="clearfix mb-0">
        <div class="float-left d-inline-block"><span class="d-none">当你找到这里表示我已经离职了, 如果有需要协助的部份请拨打 +886-0806449</span></div>
        <div class="float-right d-inline-block d-none"><span class="text-muted font-small-1">Powered by RB & Max</span></div>
    </div>
    @if($configData['isScrollTop'] === true)
        <button class="btn btn-primary btn-icon scroll-top" type="button" style="display: inline-block;">
            <i class="bx bx-up-arrow-alt"></i>
        </button>
    @endif
</footer>

